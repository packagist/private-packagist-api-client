<?php

/*
 * (c) Packagist Conductors UG (haftungsbeschränkt) <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api\Subrepositories;

use PrivatePackagist\ApiClient\Api\AbstractApi;
use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;

class Packages extends AbstractApi
{
    public function all($subrepositoryName, array $filters = [])
    {
        if (isset($filters['origin']) && !in_array($filters['origin'], \PrivatePackagist\ApiClient\Api\Packages::AVAILABLE_ORIGINS, true)) {
            throw new InvalidArgumentException('Filter "origin" has to be one of: "' . implode('", "', \PrivatePackagist\ApiClient\Api\Packages::AVAILABLE_ORIGINS) . '".');
        }

        return $this->get(sprintf('/subrepositories/%s/packages/', $subrepositoryName), $filters);
    }

    public function show($subrepositoryName, $packageName)
    {
        return $this->get(sprintf('/subrepositories/%s/packages/%s', $subrepositoryName, $packageName));
    }

    public function createVcsPackage($subrepositoryName, $url, $credentialId = null)
    {
        return $this->post(sprintf('/subrepositories/%s/packages/', $subrepositoryName), ['repoType' => 'vcs', 'repoUrl' => $url, 'credentials' => $credentialId]);
    }

    public function createCustomPackage($subrepositoryName, $customJson, $credentialId = null)
    {
        if (is_array($customJson) || is_object($customJson)) {
            $customJson = json_encode($customJson);
        }

        return $this->post(sprintf('/subrepositories/%s/packages/', $subrepositoryName), ['repoType' => 'package', 'repoConfig' => $customJson, 'credentials' => $credentialId]);
    }

    public function editVcsPackage($subrepositoryName, $packageName, $url, $credentialId = null)
    {
        return $this->put(sprintf('/subrepositories/%s/packages/%s/', $subrepositoryName, $packageName), ['repoType' => 'vcs', 'repoUrl' => $url, 'credentials' => $credentialId]);
    }

    public function editCustomPackage($subrepositoryName, $packageName, $customJson, $credentialId = null)
    {
        return $this->put(sprintf('/subrepositories/%s/packages/%s/', $subrepositoryName, $packageName), ['repoType' => 'package', 'repoConfig' => $customJson, 'credentials' => $credentialId]);
    }

    public function remove($subrepositoryName, $packageName)
    {
        return $this->delete(sprintf('/subrepositories/%s/packages/%s/', $subrepositoryName, $packageName));
    }

    public function listDependents($subrepositoryName, $packageName)
    {
        return $this->get(sprintf('/subrepositories/%s/packages/%s/dependents/', $subrepositoryName, $packageName));
    }
}
