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

class MirroredRepositories extends AbstractApi
{
    public function all($suborganizationName)
    {
        return $this->get(sprintf('/suborganizations/%s/mirrored-repositories/', $suborganizationName), ['limit' => self::DEFAULT_LIMIT]);
    }

    public function add($suborganizationName, array $mirroredRepositories)
    {
        foreach ($mirroredRepositories as $mirroredRepository) {
            if (!isset($mirroredRepository['id'], $mirroredRepository['mirroringBehavior'])) {
                throw new InvalidArgumentException('The "id" and the "mirroringBehavior" are required to add a mirrored repository to a project');
            }
        }

        return $this->post(sprintf('/suborganizations/%s/mirrored-repositories/', $suborganizationName), $mirroredRepositories);
    }

    public function show($suborganizationName, $mirroredRepositoryId)
    {
        return $this->get(sprintf('/suborganizations/%s/mirrored-repositories/%s/', $suborganizationName, $mirroredRepositoryId));
    }

    public function edit($suborganizationName, $mirroredRepositoryId, $mirroringBehavior)
    {
        return $this->put(sprintf('/suborganizations/%s/mirrored-repositories/%s/', $suborganizationName, $mirroredRepositoryId), [
            'mirroringBehavior' => $mirroringBehavior,
        ]);
    }

    public function remove($suborganizationName, $mirroredRepositoryId)
    {
        return $this->delete(sprintf('/suborganizations/%s/mirrored-repositories/%s/', $suborganizationName, $mirroredRepositoryId));
    }

    public function listPackages($suborganizationName, $mirroredRepositoryId)
    {
        return $this->get(sprintf('/suborganizations/%s/mirrored-repositories/%s/packages/', $suborganizationName, $mirroredRepositoryId), ['limit' => self::DEFAULT_LIMIT]);
    }

    public function addPackages($suborganizationName, $mirroredRepositoryId, array $packages)
    {
        return $this->post(sprintf('/suborganizations/%s/mirrored-repositories/%s/packages/', $suborganizationName, $mirroredRepositoryId), $packages);
    }

    public function removePackages($suborganizationName, $mirroredRepositoryId)
    {
        return $this->delete(sprintf('/suborganizations/%s/mirrored-repositories/%s/packages/', $suborganizationName, $mirroredRepositoryId));
    }
}
