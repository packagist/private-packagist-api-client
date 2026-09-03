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
        return $this->getCollection('/mirrored-repositories/');
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
        return $this->get($this->buildPath('/mirrored-repositories/%s/', $mirroredRepositoryId));
    }

    public function edit($mirroredRepositoryId, $name, $url, $mirroringBehavior, $credentials = null)
    {
        return $this->put($this->buildPath('/mirrored-repositories/%s/', $mirroredRepositoryId), [
            'name' => $name,
            'url' => $url,
            'mirroringBehavior' => $mirroringBehavior,
            'credentials' => $credentials,
        ]);
    }

    public function remove($mirroredRepositoryId)
    {
        return $this->delete($this->buildPath('/mirrored-repositories/%s/', $mirroredRepositoryId));
    }

    public function listPackages($mirroredRepositoryId)
    {
        return $this->getCollection($this->buildPath('/mirrored-repositories/%s/packages/', $mirroredRepositoryId));
    }

    public function addPackages($mirroredRepositoryId, array $packages)
    {
        return $this->post($this->buildPath('/mirrored-repositories/%s/packages/', $mirroredRepositoryId), $packages);
    }

    public function removePackages($mirroredRepositoryId)
    {
        return $this->delete($this->buildPath('/mirrored-repositories/%s/packages/', $mirroredRepositoryId));
    }
}
