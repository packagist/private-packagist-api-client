<?php

namespace PrivatePackagist\ApiClient\Api;

use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;

class Projects extends AbstractApi
{
    public function all()
    {
        return $this->get('/projects/');
    }

    public function show($projectId)
    {
        return $this->get(sprintf('/projects/%s/', $projectId));
    }

    public function create($name)
    {
        return $this->post('/projects/', ['name' => $name]);
    }

    public function remove($projectId)
    {
        return $this->delete(sprintf('/projects/%s/', $projectId));
    }

    public function listTeams($projectsId)
    {
        return $this->get(sprintf('/projects/%s/teams/', $projectsId));
    }

    public function addOrUpdateTeams($projectId, array $teams)
    {
        foreach ($teams as $team) {
            if (!isset($team['id'])) {
                throw new InvalidArgumentException('Parameter "id" is required.');
            }

            if (!isset($team['permission'])) {
                throw new InvalidArgumentException('Parameter "permission" is required.');
            }
        }

        return $this->post(sprintf('/projects/%s/teams/', $projectId), $teams);
    }

    public function removeTeam($projectId, $teamId)
    {
        return $this->delete(sprintf('/projects/%s/teams/%s/', $projectId, $teamId));
    }

    public function listPackages($projectsId)
    {
        return $this->get(sprintf('/projects/%s/packages/', $projectsId));
    }
}
