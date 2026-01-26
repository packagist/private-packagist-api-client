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

/**
 * @deprecated Use \PrivatePackagist\ApiClient\Api\Suborganizations\MirroredRepositories instead
 */
class MirroredRepositories extends AbstractApi
{
    public function all($subrepositoryName)
    {
        return $this->get(sprintf('/subrepositories/%s/mirrored-repositories/', $subrepositoryName), ['limit' => self::DEFAULT_LIMIT]);
    }

    public function add($subrepositoryName, array $mirroredRepositories)
    {
        foreach ($mirroredRepositories as $mirroredRepository) {
            if (!isset($mirroredRepository['id'], $mirroredRepository['mirroringBehavior'])) {
                throw new InvalidArgumentException('The "id" and the "mirroringBehavior" are required to add a mirrored repository to a project');
            }
        }

        return $this->post(sprintf('/subrepositories/%s/mirrored-repositories/', $subrepositoryName), $mirroredRepositories);
    }

    public function show($subrepositoryName, $mirroredRepositoryId)
    {
        return $this->get(sprintf('/subrepositories/%s/mirrored-repositories/%s/', $subrepositoryName, $mirroredRepositoryId));
    }

    public function edit($subrepositoryName, $mirroredRepositoryId, $mirroringBehavior)
    {
        return $this->put(sprintf('/subrepositories/%s/mirrored-repositories/%s/', $subrepositoryName, $mirroredRepositoryId), [
            'mirroringBehavior' => $mirroringBehavior,
        ]);
    }

    public function remove($subrepositoryName, $mirroredRepositoryId)
    {
        return $this->delete(sprintf('/subrepositories/%s/mirrored-repositories/%s/', $subrepositoryName, $mirroredRepositoryId));
    }

    public function listPackages($subrepositoryName, $mirroredRepositoryId)
    {
        return $this->get(sprintf('/subrepositories/%s/mirrored-repositories/%s/packages/', $subrepositoryName, $mirroredRepositoryId), ['limit' => self::DEFAULT_LIMIT]);
    }

    public function addPackages($subrepositoryName, $mirroredRepositoryId, array $packages)
    {
        return $this->post(sprintf('/subrepositories/%s/mirrored-repositories/%s/packages/', $subrepositoryName, $mirroredRepositoryId), $packages);
    }

    public function removePackages($subrepositoryName, $mirroredRepositoryId)
    {
        return $this->delete(sprintf('/subrepositories/%s/mirrored-repositories/%s/packages/', $subrepositoryName, $mirroredRepositoryId));
    }
}
