<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api\Subrepositories;

use PrivatePackagist\ApiClient\Api\AbstractApi;
use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;
use PrivatePackagist\ApiClient\Payload\CustomPackageConfig;
use PrivatePackagist\ApiClient\Payload\VcsPackageConfig;

/**
 *  @deprecated Use \PrivatePackagist\ApiClient\Api\Suborganizations\Packages instead
 */
class Packages extends AbstractApi
{
    public function all($subrepositoryName, array $filters = [])
    {
        if (isset($filters['origin']) && !in_array($filters['origin'], \PrivatePackagist\ApiClient\Api\Packages::AVAILABLE_ORIGINS, true)) {
            throw new InvalidArgumentException('Filter "origin" has to be one of: "' . implode('", "', \PrivatePackagist\ApiClient\Api\Packages::AVAILABLE_ORIGINS) . '".');
        }

        $filters = array_merge(['limit' => self::DEFAULT_LIMIT], $filters);

        return $this->get(sprintf('/subrepositories/%s/packages/', $subrepositoryName), $filters);
    }

    public function show($subrepositoryName, $packageIdOrName)
    {
        return $this->get(sprintf('/subrepositories/%s/packages/%s/', $subrepositoryName, $packageIdOrName));
    }

    public function createVcsPackage($subrepositoryName, $url, $credentialId = null, $type = 'vcs', $defaultSubrepositoryAccess = null)
    {
        $data = new VcsPackageConfig($url, $credentialId, $type, $defaultSubrepositoryAccess);

        return $this->post(sprintf('/subrepositories/%s/packages/', $subrepositoryName), $data->toParameters());
    }

    public function createCustomPackage($subrepositoryName, $customJson, $credentialId = null, $defaultSubrepositoryAccess = null)
    {
        $data = new CustomPackageConfig($customJson, $credentialId, $defaultSubrepositoryAccess);

        return $this->post(sprintf('/subrepositories/%s/packages/', $subrepositoryName), $data->toParameters());
    }

    public function editVcsPackage($subrepositoryName, $packageIdOrName, $url, $credentialId = null, $type = 'vcs', $defaultSubrepositoryAccess = null)
    {
        $data = new VcsPackageConfig($url, $credentialId, $type, $defaultSubrepositoryAccess);

        return $this->put(sprintf('/subrepositories/%s/packages/%s/', $subrepositoryName, $packageIdOrName), $data->toParameters());
    }

    public function editCustomPackage($subrepositoryName, $packageIdOrName, $customJson, $credentialId = null, $defaultSubrepositoryAccess = null)
    {
        $data = new CustomPackageConfig($customJson, $credentialId, $defaultSubrepositoryAccess);

        return $this->put(sprintf('/subrepositories/%s/packages/%s/', $subrepositoryName, $packageIdOrName), $data->toParameters());
    }

    public function remove($subrepositoryName, $packageIdOrName)
    {
        return $this->delete(sprintf('/subrepositories/%s/packages/%s/', $subrepositoryName, $packageIdOrName));
    }

    public function listDependents($subrepositoryName, $packageIdOrName)
    {
        return $this->get(sprintf('/subrepositories/%s/packages/%s/dependents/', $subrepositoryName, $packageIdOrName), ['limit' => self::DEFAULT_LIMIT]);
    }
}
