<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

class VendorBundles extends AbstractApi
{
    /**
     * @return array[]
     */
    public function all()
    {
        return $this->get('/vendor-bundles/', ['limit' => self::DEFAULT_LIMIT]);
    }

    /**
     * @param int $vendorBundleId
     * @return array
     */
    public function show($vendorBundleId)
    {
        return $this->get(sprintf('/vendor-bundles/%s/', $vendorBundleId));
    }

    /**
     * @param string $name
     * @param string|null $minimumAccessibleStability
     * @param string|null $versionConstraint
     * @param bool $assignAllPackages
     * @param int[] $synchronizationIds
     */
    public function create($name, $minimumAccessibleStability = null, $versionConstraint = null, $assignAllPackages = false, array $synchronizationIds = [])
    {
        return $this->post('/vendor-bundles/', [
            'name' => $name,
            'minimumAccessibleStability' => $minimumAccessibleStability,
            'versionConstraint' => $versionConstraint,
            'assignAllPackages' => $assignAllPackages,
            'synchronizationIds' => $synchronizationIds,
        ]);
    }

    /**
     * @param int $vendorBundleId
     * @param array{name: string, minimumAccessibleStability?: string, versionConstraint?: string, assignAllPackages: bool, synchronizationIds?: int[]} $bundle
     * @return array
     */
    public function edit($vendorBundleId, array $bundle)
    {
        return $this->put(sprintf('/vendor-bundles/%s/', $vendorBundleId), $bundle);
    }

    /**
     * @param int $vendorBundleId
     */
    public function remove($vendorBundleId)
    {
        return $this->delete(sprintf('/vendor-bundles/%s/', $vendorBundleId));
    }

    public function packages(): \PrivatePackagist\ApiClient\Api\VendorBundles\Packages
    {
        return new \PrivatePackagist\ApiClient\Api\VendorBundles\Packages($this->client);
    }
}
