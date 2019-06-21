<?php

/*
 * (c) Packagist Conductors UG (haftungsbeschränkt) <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api\Projects;

use PrivatePackagist\ApiClient\Api\AbstractApi;
use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;

class Packages extends AbstractApi
{
    public function all($projectName, array $filters = [])
    {
        if (isset($filters['origin']) && !in_array($filters['origin'], \PrivatePackagist\ApiClient\Api\Packages::AVAILABLE_ORIGINS, true)) {
            throw new InvalidArgumentException('Filter "origin" has to be one of: "' . implode('", "', \PrivatePackagist\ApiClient\Api\Packages::AVAILABLE_ORIGINS) . '".');
        }

        return $this->get(sprintf('/projects/%s/packages/', $projectName), $filters);
    }

    public function show($projectName, $packageName)
    {
        return $this->get(sprintf('/projects/%s/packages/%s', $projectName, $packageName));
    }

    public function createVcsPackage($projectName, $url, $credentialId = null)
    {
        return $this->post(sprintf('/projects/%s/packages/', $projectName), ['repoType' => 'vcs', 'repoUrl' => $url, 'credentials' => $credentialId]);
    }

    public function createCustomPackage($projectName, $customJson, $credentialId = null)
    {
        if (is_array($customJson) || is_object($customJson)) {
            $customJson = json_encode($customJson);
        }

        return $this->post(sprintf('/projects/%s/packages/', $projectName), ['repoType' => 'package', 'repoConfig' => $customJson, 'credentials' => $credentialId]);
    }

    public function editVcsPackage($projectName, $packageName, $url, $credentialId = null)
    {
        return $this->put(sprintf('/projects/%s/packages/%s/', $projectName, $packageName), ['repoType' => 'vcs', 'repoUrl' => $url, 'credentials' => $credentialId]);
    }

    public function editCustomPackage($projectName, $packageName, $customJson, $credentialId = null)
    {
        return $this->put(sprintf('/projects/%s/packages/%s/', $projectName, $packageName), ['repoType' => 'package', 'repoConfig' => $customJson, 'credentials' => $credentialId]);
    }

    public function remove($projectName, $packageName)
    {
        return $this->delete(sprintf('/projects/%s/packages/%s/', $projectName, $packageName));
    }
}
