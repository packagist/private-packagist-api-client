<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;

class Suborganizations extends AbstractApi
{
    public function all()
    {
        return $this->get('/suborganizations/', ['limit' => self::DEFAULT_LIMIT]);
    }

    public function show($suborganizationName)
    {
        return $this->get(sprintf('/suborganizations/%s/', $suborganizationName));
    }

    public function create($name)
    {
        return $this->post('/suborganizations/', ['name' => $name]);
    }

    public function remove($suborganizationName)
    {
        return $this->delete(sprintf('/suborganizations/%s/', $suborganizationName));
    }

    public function listTeams($suborganizationName)
    {
        return $this->get(sprintf('/suborganizations/%s/teams/', $suborganizationName), ['limit' => self::DEFAULT_LIMIT]);
    }

    public function addOrEditTeams($suborganizationName, array $teams)
    {
        foreach ($teams as $team) {
            if (!isset($team['id'])) {
                throw new InvalidArgumentException('Parameter "id" is required.');
            }

            if (!isset($team['permission'])) {
                throw new InvalidArgumentException('Parameter "permission" is required.');
            }
        }

        return $this->post(sprintf('/suborganizations/%s/teams/', $suborganizationName), $teams);
    }

    public function removeTeam($suborganizationName, $teamId)
    {
        return $this->delete(sprintf('/suborganizations/%s/teams/%s/', $suborganizationName, $teamId));
    }

    public function listTokens($suborganizationName)
    {
        return $this->get(sprintf('/suborganizations/%s/tokens/', $suborganizationName), ['limit' => self::DEFAULT_LIMIT]);
    }

    public function createToken($suborganizationName, array $tokenData)
    {
        return $this->post(sprintf('/suborganizations/%s/tokens/', $suborganizationName), $tokenData);
    }

    public function removeToken($suborganizationName, $tokenId)
    {
        return $this->delete(sprintf('/suborganizations/%s/tokens/%s/', $suborganizationName, $tokenId));
    }

    public function regenerateToken($suborganizationName, $tokenId, array $confirmation)
    {
        if (!isset($confirmation['IConfirmOldTokenWillStopWorkingImmediately'])) {
            throw new InvalidArgumentException('Confirmation is required to regenerate the Composer repository token.');
        }

        return $this->post(sprintf('/suborganizations/%s/tokens/%s/regenerate', $suborganizationName, $tokenId), $confirmation);
    }

    public function packages()
    {
        return new Suborganizations\Packages($this->client);
    }

    public function mirroredRepositories()
    {
        return new Suborganizations\MirroredRepositories($this->client);
    }
}
