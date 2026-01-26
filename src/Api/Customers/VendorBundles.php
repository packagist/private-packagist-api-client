<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api\Customers;

use PrivatePackagist\ApiClient\Api\AbstractApi;

class VendorBundles extends AbstractApi
{
    public function listVendorBundles($customerIdOrUrlName)
    {
        return $this->get(sprintf('/customers/%s/vendor-bundles/', $customerIdOrUrlName), ['limit' => self::DEFAULT_LIMIT]);
    }

    /**
     * @param int|string $customerIdOrUrlName
     * @param int $vendorBundleId
     * @param null|string $expirationDate
     */
    public function addOrEditVendorBundle($customerIdOrUrlName, $vendorBundleId, $expirationDate = null)
    {
        return $this->post(sprintf('/customers/%s/vendor-bundles/', $customerIdOrUrlName), [
            'vendorBundleId' => $vendorBundleId,
            'expirationDate' => $expirationDate,
        ]);
    }

    /**
     * @param int|string $customerIdOrUrlName
     * @param int $vendorBundleId
     */
    public function removeVendorBundle($customerIdOrUrlName, $vendorBundleId)
    {
        return $this->delete(sprintf('/customers/%s/vendor-bundles/%s/', $customerIdOrUrlName, $vendorBundleId));
    }
}
