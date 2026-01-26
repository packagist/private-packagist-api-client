<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

class MirroredRepositories extends AbstractApi
{
    public function all()
    {
        return $this->get('/mirrored-repositories/', ['limit' => self::DEFAULT_LIMIT]);
    }

    public function create($name, $url, $mirroringBehavior, $credentials = null)
    {
        return $this->post('/mirrored-repositories/', [
            'name' => $name,
            'url' => $url,
            'mirroringBehavior' => $mirroringBehavior,
            'credentials' => $credentials,
        ]);
    }

    public function show($mirroredRepositoryId)
    {
        return $this->get(sprintf('/mirrored-repositories/%s/', $mirroredRepositoryId));
    }

    public function edit($mirroredRepositoryId, $name, $url, $mirroringBehavior, $credentials = null)
    {
        return $this->put(sprintf('/mirrored-repositories/%s/', $mirroredRepositoryId), [
            'name' => $name,
            'url' => $url,
            'mirroringBehavior' => $mirroringBehavior,
            'credentials' => $credentials,
        ]);
    }

    public function remove($mirroredRepositoryId)
    {
        return $this->delete(sprintf('/mirrored-repositories/%s/', $mirroredRepositoryId));
    }

    public function listPackages($mirroredRepositoryId)
    {
        return $this->get(sprintf('/mirrored-repositories/%s/packages/', $mirroredRepositoryId), ['limit' => self::DEFAULT_LIMIT]);
    }

    public function addPackages($mirroredRepositoryId, array $packages)
    {
        return $this->post(sprintf('/mirrored-repositories/%s/packages/', $mirroredRepositoryId), $packages);
    }

    public function removePackages($mirroredRepositoryId)
    {
        return $this->delete(sprintf('/mirrored-repositories/%s/packages/', $mirroredRepositoryId));
    }
}
