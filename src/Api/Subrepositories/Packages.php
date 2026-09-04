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

        return $this->getCollection($this->buildPath('/subrepositories/%s/packages/', $subrepositoryName), $filters);
    }

    public function show($subrepositoryName, $packageIdOrName)
    {
        return $this->get($this->buildPath('/subrepositories/%s/packages/%s/', $subrepositoryName, $packageIdOrName));
    }

    public function createVcsPackage($subrepositoryName, $url, $credentialId = null, $type = 'vcs', $defaultSuborganizationAccess = null)
    {
        $data = new VcsPackageConfig($url, $credentialId, $type, $defaultSuborganizationAccess);

        return $this->post($this->buildPath('/subrepositories/%s/packages/', $subrepositoryName), $data->toParameters());
    }

    public function createCustomPackage($subrepositoryName, $customJson, $credentialId = null, $defaultSuborganizationAccess = null)
    {
        $data = new CustomPackageConfig($customJson, $credentialId, $defaultSuborganizationAccess);

        return $this->post($this->buildPath('/subrepositories/%s/packages/', $subrepositoryName), $data->toParameters());
    }

    public function editVcsPackage($subrepositoryName, $packageIdOrName, $url, $credentialId = null, $type = 'vcs', $defaultSuborganizationAccess = null)
    {
        $data = new VcsPackageConfig($url, $credentialId, $type, $defaultSuborganizationAccess);

        return $this->put($this->buildPath('/subrepositories/%s/packages/%s/', $subrepositoryName, $packageIdOrName), $data->toParameters());
    }

    public function editCustomPackage($subrepositoryName, $packageIdOrName, $customJson, $credentialId = null, $defaultSuborganizationAccess = null)
    {
        $data = new CustomPackageConfig($customJson, $credentialId, $defaultSuborganizationAccess);

        return $this->put($this->buildPath('/subrepositories/%s/packages/%s/', $subrepositoryName, $packageIdOrName), $data->toParameters());
    }

    public function remove($subrepositoryName, $packageIdOrName)
    {
        return $this->delete($this->buildPath('/subrepositories/%s/packages/%s/', $subrepositoryName, $packageIdOrName));
    }

    public function listDependents($subrepositoryName, $packageIdOrName)
    {
        return $this->getCollection($this->buildPath('/subrepositories/%s/packages/%s/dependents/', $subrepositoryName, $packageIdOrName));
    }
}
