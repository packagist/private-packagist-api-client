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

class MirroredRepositories extends AbstractApi
{
    public function all($projectName)
    {
        return $this->get(sprintf('/projects/%s/mirrored-repositories/', $projectName));
    }

    public function add($projectName, array $mirroredRepositories)
    {
        foreach ($mirroredRepositories as $mirroredRepository) {
            if (!isset($mirroredRepository['id'], $mirroredRepository['mirroringBehavior'])) {
                throw new InvalidArgumentException('The "id" and the "mirroringBehavior" are required to add a mirrored repository to a project');
            }
        }

        return $this->post(sprintf('/projects/%s/mirrored-repositories/', $projectName), $mirroredRepositories);
    }

    public function show($projectName, $mirroredRepositoryId)
    {
        return $this->get(sprintf('/projects/%s/mirrored-repositories/%s/', $projectName, $mirroredRepositoryId));
    }

    public function edit($projectName, $mirroredRepositoryId, $mirroringBehavior)
    {
        return $this->put(sprintf('/projects/%s/mirrored-repositories/%s/', $projectName, $mirroredRepositoryId), [
            'mirroringBehavior' => $mirroringBehavior,
        ]);
    }

    public function remove($projectName, $mirroredRepositoryId)
    {
        return $this->delete(sprintf('/projects/%s/mirrored-repositories/%s/', $projectName, $mirroredRepositoryId));
    }

    public function listPackages($projectName, $mirroredRepositoryId)
    {
        return $this->get(sprintf('/projects/%s/mirrored-repositories/%s/packages/', $projectName, $mirroredRepositoryId));
    }

    public function addPackages($projectName, $mirroredRepositoryId, array $packages)
    {
        return $this->post(sprintf('/projects/%s/mirrored-repositories/%s/packages/', $projectName, $mirroredRepositoryId), $packages);
    }

    public function removePackages($projectName, $mirroredRepositoryId)
    {
        return $this->delete(sprintf('/projects/%s/mirrored-repositories/%s/packages/', $projectName, $mirroredRepositoryId));
    }
}
