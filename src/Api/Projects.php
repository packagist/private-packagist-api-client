<?php

namespace PrivatePackagist\ApiClient\Api;

use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;

class Projects extends AbstractApi
{
    public function all()
    {
        return $this->get('/projects/');
    }

    public function show($projectName)
    {
        return $this->get(sprintf('/projects/%s/', $projectName));
    }

    public function create($name)
    {
        return $this->post('/projects/', ['name' => $name]);
    }

    public function remove($projectName)
    {
        return $this->delete(sprintf('/projects/%s/', $projectName));
    }

    public function listTeams($projectsName)
    {
        return $this->get(sprintf('/projects/%s/teams/', $projectsName));
    }

    public function addOrUpdateTeams($projectName, array $teams)
    {
        foreach ($teams as $team) {
            if (!isset($team['id'])) {
                throw new InvalidArgumentException('Parameter "id" is required.');
            }

            if (!isset($team['permission'])) {
                throw new InvalidArgumentException('Parameter "permission" is required.');
            }
        }

        return $this->post(sprintf('/projects/%s/teams/', $projectName), $teams);
    }

    public function removeTeam($projectName, $teamId)
    {
        return $this->delete(sprintf('/projects/%s/teams/%s/', $projectName, $teamId));
    }

    public function listPackages($projectName)
    {
        return $this->get(sprintf('/projects/%s/packages/', $projectName));
    }

    public function listTokens($projectName)
    {
        return $this->get(sprintf('/projects/%s/tokens/', $projectName));
    }

    public function createToken($projectName, array $tokenData)
    {
        return $this->post(sprintf('/projects/%s/tokens/', $projectName), $tokenData);
    }

    public function removeToken($projectName, $tokenId)
    {
        return $this->delete(sprintf('/projects/%s/tokens/%s/', $projectName, $tokenId));
    }

    public function regenerateToken($projectName, $tokenId, array $confirmation)
    {
        if (!isset($confirmation['IConfirmOldTokenWillStopWorkingImmediately'])) {
            throw new InvalidArgumentException('Confirmation is required to regenerate the Composer repository token.');
        }

        return $this->post(sprintf('/projects/%s/tokens/%s/regenerate', $projectName, $tokenId), $confirmation);
    }
}
