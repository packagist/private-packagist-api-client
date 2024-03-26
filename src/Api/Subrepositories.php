<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;

class Subrepositories extends AbstractApi
{
    public function all()
    {
        return $this->get('/subrepositories/');
    }

    public function show($subrepositoryName)
    {
        return $this->get(sprintf('/subrepositories/%s/', $subrepositoryName));
    }

    public function create($name)
    {
        return $this->post('/subrepositories/', ['name' => $name]);
    }

    public function remove($subrepositoryName)
    {
        return $this->delete(sprintf('/subrepositories/%s/', $subrepositoryName));
    }

    public function listTeams($subrepositoryName)
    {
        return $this->get(sprintf('/subrepositories/%s/teams/', $subrepositoryName));
    }

    /**
     * @deprecated Use addOrEditTeams instead
     */
    public function addOrUpdateTeams($subrepositoryName, array $teams)
    {
        return $this->addOrEditTeams($subrepositoryName, $teams);
    }

    public function addOrEditTeams($subrepositoryName, array $teams)
    {
        foreach ($teams as $team) {
            if (!isset($team['id'])) {
                throw new InvalidArgumentException('Parameter "id" is required.');
            }

            if (!isset($team['permission'])) {
                throw new InvalidArgumentException('Parameter "permission" is required.');
            }
        }

        return $this->post(sprintf('/subrepositories/%s/teams/', $subrepositoryName), $teams);
    }

    public function removeTeam($subrepositoryName, $teamId)
    {
        return $this->delete(sprintf('/subrepositories/%s/teams/%s/', $subrepositoryName, $teamId));
    }

    /**
     * @deprecated use packages()->all()
     */
    public function listPackages($subrepositoryName)
    {
        return $this->packages()->all($subrepositoryName);
    }

    public function listTokens($subrepositoryName)
    {
        return $this->get(sprintf('/subrepositories/%s/tokens/', $subrepositoryName));
    }

    public function createToken($subrepositoryName, array $tokenData)
    {
        return $this->post(sprintf('/subrepositories/%s/tokens/', $subrepositoryName), $tokenData);
    }

    public function removeToken($subrepositoryName, $tokenId)
    {
        return $this->delete(sprintf('/subrepositories/%s/tokens/%s/', $subrepositoryName, $tokenId));
    }

    public function regenerateToken($subrepositoryName, $tokenId, array $confirmation)
    {
        if (!isset($confirmation['IConfirmOldTokenWillStopWorkingImmediately'])) {
            throw new InvalidArgumentException('Confirmation is required to regenerate the Composer repository token.');
        }

        return $this->post(sprintf('/subrepositories/%s/tokens/%s/regenerate', $subrepositoryName, $tokenId), $confirmation);
    }

    public function packages()
    {
        return new Subrepositories\Packages($this->client);
    }

    public function mirroredRepositories()
    {
        return new Subrepositories\MirroredRepositories($this->client);
    }
}
