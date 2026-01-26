<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use PrivatePackagist\ApiClient\Api\Customers\MagentoLegacyKeys;
use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;

class Customers extends AbstractApi
{
    public function all()
    {
        return $this->get('/customers/', ['limit' => self::DEFAULT_LIMIT]);
    }

    public function show($customerIdOrUrlName)
    {
        return $this->get(sprintf('/customers/%s/', $customerIdOrUrlName));
    }

    public function create($name, $accessToVersionControlSource = false, $urlName = null, $minimumAccessibleStability = null, $assignAllPackages = false)
    {
        $parameters = [
            'name' => $name,
            'accessToVersionControlSource' => $accessToVersionControlSource,
            'minimumAccessibleStability' => $minimumAccessibleStability,
            'assignAllPackages' => $assignAllPackages,
        ];
        if ($urlName) {
            $parameters['urlName'] = $urlName;
        }

        return $this->post('/customers/', $parameters);
    }

    /**
     * @deprecated Use edit instead
     */
    #[\Deprecated('Use Customers::edit instead', '1.11.0')]
    public function update($customerIdOrUrlName, array $customer)
    {
        return $this->edit($customerIdOrUrlName, $customer);
    }

    public function edit($customerIdOrUrlName, array $customer)
    {
        return $this->put(sprintf('/customers/%s/', $customerIdOrUrlName), $customer);
    }

    public function remove($customerIdOrUrlName)
    {
        return $this->delete(sprintf('/customers/%s/', $customerIdOrUrlName));
    }

    public function enable($customerIdOrUrlName)
    {
        return $this->put(sprintf('/customers/%s/enable', $customerIdOrUrlName));
    }

    public function disable($customerIdOrUrlName)
    {
        return $this->put(sprintf('/customers/%s/disable', $customerIdOrUrlName));
    }

    public function listPackages($customerIdOrUrlName)
    {
        return $this->get(sprintf('/customers/%s/packages/', $customerIdOrUrlName), ['limit' => self::DEFAULT_LIMIT]);
    }

    public function showPackage($customerIdOrUrlName, $packageIdOrName)
    {
        return $this->get(sprintf('/customers/%s/packages/%s/', $customerIdOrUrlName, $packageIdOrName));
    }

    /**
     * @deprecated Use addOrEditPackages instead
     */
    #[\Deprecated('Use Customers::addOrEditPackages instead', '1.11.0')]
    public function addOrUpdatePackages($customerIdOrUrlName, array $packages)
    {
        return $this->addOrEditPackages($customerIdOrUrlName, $packages);
    }

    public function addOrEditPackages($customerIdOrUrlName, array $packages)
    {
        foreach ($packages as $package) {
            if (!isset($package['name'])) {
                throw new InvalidArgumentException('Parameter "name" is required.');
            }
        }

        return $this->post(sprintf('/customers/%s/packages/', $customerIdOrUrlName), $packages);
    }

    /**
     * @deprecated Use addOrEditPackages instead
     */
    #[\Deprecated('Use Customers::addOrEditPackages instead', '1.11.0')]
    public function addPackages($customerIdOrUrlName, array $packages)
    {
        return $this->addOrEditPackages($customerIdOrUrlName, $packages);
    }

    public function removePackage($customerIdOrUrlName, $packageIdOrName)
    {
        return $this->delete(sprintf('/customers/%s/packages/%s/', $customerIdOrUrlName, $packageIdOrName));
    }

    public function regenerateToken($customerIdOrUrlName, array $confirmation)
    {
        if (!isset($confirmation['IConfirmOldTokenWillStopWorkingImmediately'])) {
            throw new InvalidArgumentException('Confirmation is required to regenerate the Composer repository token.');
        }

        return $this->post(sprintf('/customers/%s/token/regenerate', $customerIdOrUrlName), $confirmation);
    }

    public function magentoLegacyKeys()
    {
        return new MagentoLegacyKeys($this->client);
    }

    public function vendorBundles()
    {
        return new \PrivatePackagist\ApiClient\Api\Customers\VendorBundles($this->client);
    }
}
