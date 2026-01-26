<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use PrivatePackagist\ApiClient\Api\Packages\Artifacts;
use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;
use PrivatePackagist\ApiClient\Payload\ArtifactPackageConfig;
use PrivatePackagist\ApiClient\Payload\CustomPackageConfig;
use PrivatePackagist\ApiClient\Payload\VcsPackageConfig;

class Packages extends AbstractApi
{
    /**
     * Packages that are mirrored from a public mirrored third party repository like packagist.org.
     */
    const ORIGIN_PUBLIC_MIRROR = 'public-mirror';

    /**
     * Packages that are mirrored from a private mirrored third party repository requiring authentication like repo.magento.com.
     */
    const ORIGIN_PRIVATE_MIRROR = 'private-mirror';

    /**
     * All other packages from a VCS repository or a custom JSON definition.
     */
    const ORIGIN_PRIVATE = 'private';

    /**
     * @deprecated Use Packages::ORIGIN_PUBLIC_MIRROR instead
     */
    #[\Deprecated('Use Packages::ORIGIN_PUBLIC_MIRROR instead', '1.13.0')]
    const ORIGIN_PUBLIC_PROXY = self::ORIGIN_PUBLIC_MIRROR;

    /**
     * @deprecated Use Packages::ORIGIN_PRIVATE_MIRROR instead
     */
    #[\Deprecated('Use Packages::ORIGIN_PRIVATE_MIRROR instead', '1.13.0')]
    const ORIGIN_PRIVATE_PROXY = self::ORIGIN_PRIVATE_MIRROR;

    const AVAILABLE_ORIGINS = [self::ORIGIN_PUBLIC_MIRROR, self::ORIGIN_PRIVATE_MIRROR, self::ORIGIN_PRIVATE, 'public-proxy', 'private-proxy'];

    public function all(array $filters = [])
    {
        if (isset($filters['origin']) && !in_array($filters['origin'], self::AVAILABLE_ORIGINS, true)) {
            throw new InvalidArgumentException('Filter "origin" has to be one of: "' . implode('", "', self::AVAILABLE_ORIGINS) . '".');
        }

        $filters = array_merge(['limit' => self::DEFAULT_LIMIT], $filters);

        return $this->getCollection('/packages/', $filters);
    }

    public function show($packageIdOrName)
    {
        return $this->get(sprintf('/packages/%s/', $packageIdOrName));
    }

    public function createVcsPackage($url, $credentialId = null, $type = 'vcs', $defaultSubrepositoryAccess = null)
    {
        $data = new VcsPackageConfig($url, $credentialId, $type, $defaultSubrepositoryAccess);

        return $this->post('/packages/', $data->toParameters());
    }

    public function createCustomPackage($customJson, $credentialId = null, $defaultSubrepositoryAccess = null)
    {
        $data = new CustomPackageConfig($customJson, $credentialId, $defaultSubrepositoryAccess);

        return $this->post('/packages/', $data->toParameters());
    }

    public function createArtifactPackage(array $artifactPackageFileIds, $defaultSubrepositoryAccess = null)
    {
        $data = new ArtifactPackageConfig($artifactPackageFileIds, $defaultSubrepositoryAccess);

        return $this->post('/packages/', $data->toParameters());
    }

    /**
     * @deprecated Use editVcsPackage instead
     */
    #[\Deprecated('Use Packages::editVcsPackage instead', '1.11.0')]
    public function updateVcsPackage($packageName, $url, $credentialId = null)
    {
        return $this->editVcsPackage($packageName, $url, $credentialId);
    }

    public function editVcsPackage($packageIdOrName, $url, $credentialId = null, $type = 'vcs', $defaultSubrepositoryAccess = null)
    {
        $data = new VcsPackageConfig($url, $credentialId, $type, $defaultSubrepositoryAccess);

        return $this->put(sprintf('/packages/%s/', $packageIdOrName), $data->toParameters());
    }

    public function editArtifactPackage($packageIdOrName, array $artifactPackageFileIds, $defaultSubrepositoryAccess = null)
    {
        $data = new ArtifactPackageConfig($artifactPackageFileIds, $defaultSubrepositoryAccess);

        return $this->put(sprintf('/packages/%s/', $packageIdOrName), $data->toParameters());
    }

    /**
     * @deprecated Use editCustomPackage instead
     */
    #[\Deprecated('Use Packages::editCustomPackage instead', '1.11.0')]
    public function updateCustomPackage($packageName, $customJson, $credentialId = null)
    {
        return $this->editCustomPackage($packageName, $customJson, $credentialId);
    }

    public function editCustomPackage($packageIdOrName, $customJson, $credentialId = null, $defaultSubrepositoryAccess = null)
    {
        $data = new CustomPackageConfig($customJson, $credentialId, $defaultSubrepositoryAccess);

        return $this->put(sprintf('/packages/%s/', $packageIdOrName), $data->toParameters());
    }

    public function remove($packageIdOrName)
    {
        return $this->delete(sprintf('/packages/%s/', $packageIdOrName));
    }

    public function listCustomers($packageIdOrName)
    {
        return $this->get(sprintf('/packages/%s/customers/', $packageIdOrName), ['limit' => self::DEFAULT_LIMIT]);
    }

    public function listDependents($packageName)
    {
        return $this->get(sprintf('/packages/%s/dependents/', $packageName), ['limit' => self::DEFAULT_LIMIT]);
    }

    public function listSecurityIssues($packageIdOrName, array $filters = [])
    {
        $filters = array_merge(['limit' => self::DEFAULT_LIMIT], $filters);

        return $this->get(sprintf('/packages/%s/security-issues/', $packageIdOrName), $filters);
    }

    public function showSecurityMonitoringConfig($packageIdOrName)
    {
        return $this->get(sprintf('/packages/%s/security-monitoring/', $packageIdOrName));
    }

    public function editSecurityMonitoringConfig($packageIdOrName, array $config)
    {
        return $this->put(sprintf('/packages/%s/security-monitoring/', $packageIdOrName), $config);
    }

    public function artifacts()
    {
        return new Artifacts($this->client, $this->client->getResponseMediator());
    }
}
