<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api\Suborganizations;

use PrivatePackagist\ApiClient\Api\AbstractApi;
use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;
use PrivatePackagist\ApiClient\Payload\CustomPackageConfig;
use PrivatePackagist\ApiClient\Payload\VcsPackageConfig;

class Packages extends AbstractApi
{
    public function all($suborganizationName, array $filters = [])
    {
        if (isset($filters['origin']) && !in_array($filters['origin'], \PrivatePackagist\ApiClient\Api\Packages::AVAILABLE_ORIGINS, true)) {
            throw new InvalidArgumentException('Filter "origin" has to be one of: "' . implode('", "', \PrivatePackagist\ApiClient\Api\Packages::AVAILABLE_ORIGINS) . '".');
        }

        $filters = array_merge(['limit' => self::DEFAULT_LIMIT], $filters);

        return $this->get(sprintf('/suborganizations/%s/packages/', $suborganizationName), $filters);
    }

    public function show($suborganizationName, $packageIdOrName)
    {
        return $this->get(sprintf('/suborganizations/%s/packages/%s/', $suborganizationName, $packageIdOrName));
    }

    public function createVcsPackage($suborganizationName, $url, $credentialId = null, $type = 'vcs', $defaultSuborganizationAccess = null)
    {
        $data = new VcsPackageConfig($url, $credentialId, $type, $defaultSuborganizationAccess);

        return $this->post(sprintf('/suborganizations/%s/packages/', $suborganizationName), $data->toParameters());
    }

    public function createCustomPackage($suborganizationName, $customJson, $credentialId = null, $defaultSuborganizationAccess = null)
    {
        $data = new CustomPackageConfig($customJson, $credentialId, $defaultSuborganizationAccess);

        return $this->post(sprintf('/suborganizations/%s/packages/', $suborganizationName), $data->toParameters());
    }

    public function editVcsPackage($suborganizationName, $packageIdOrName, $url, $credentialId = null, $type = 'vcs', $defaultSuborganizationAccess = null)
    {
        $data = new VcsPackageConfig($url, $credentialId, $type, $defaultSuborganizationAccess);

        return $this->put(sprintf('/suborganizations/%s/packages/%s/', $suborganizationName, $packageIdOrName), $data->toParameters());
    }

    public function editCustomPackage($suborganizationName, $packageIdOrName, $customJson, $credentialId = null, $defaultSuborganizationAccess = null)
    {
        $data = new CustomPackageConfig($customJson, $credentialId, $defaultSuborganizationAccess);

        return $this->put(sprintf('/suborganizations/%s/packages/%s/', $suborganizationName, $packageIdOrName), $data->toParameters());
    }

    public function remove($suborganizationName, $packageIdOrName)
    {
        return $this->delete(sprintf('/suborganizations/%s/packages/%s/', $suborganizationName, $packageIdOrName));
    }

    public function listDependents($suborganizationName, $packageIdOrName)
    {
        return $this->get(sprintf('/suborganizations/%s/packages/%s/dependents/', $suborganizationName, $packageIdOrName), ['limit' => self::DEFAULT_LIMIT]);
    }
}
