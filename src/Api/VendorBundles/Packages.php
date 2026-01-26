<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api\VendorBundles;

use PrivatePackagist\ApiClient\Api\AbstractApi;
use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;

class Packages extends AbstractApi
{
    /**
     * @param int $vendorBundleIds
     * @return array[]
     */
    public function listPackages($vendorBundleIds)
    {
        return $this->get(sprintf('/vendor-bundles/%s/packages/', $vendorBundleIds), ['limit' => self::DEFAULT_LIMIT]);
    }

    /**
     * @param int $vendorBundleId
     * @param array{name: string, versionConstraint?: string, minimumAccessibleStability?: string}[] $packages
     * @return array[]
     */
    public function addOrEditPackages($vendorBundleId, array $packages)
    {
        foreach ($packages as $package) {
            if (!isset($package['name'])) { // @phpstan-ignore-line
                throw new InvalidArgumentException('Parameter "name" is required.');
            }
        }

        return $this->post(sprintf('/vendor-bundles/%s/packages/', $vendorBundleId), $packages);
    }

    /**
     * @param int $vendorBundleId
     * @param string|int $packageIdOrName
     */
    public function removePackage($vendorBundleId, $packageIdOrName)
    {
        return $this->delete(sprintf('/vendor-bundles/%s/packages/%s/', $vendorBundleId, $packageIdOrName));
    }
}
