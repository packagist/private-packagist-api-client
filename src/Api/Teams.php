<?php

/*
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use PrivatePackagist\ApiClient\TeamPermissions;

class Teams extends AbstractApi
{
    public function all()
    {
        return $this->get('/teams/');
    }

    public function create(string $name, TeamPermissions $permissions, bool $canAccessAllPackages = false): array
    {
        $parameters = [
            'name' => $name,
            'permissions' => [
                'canEditTeamPackages' => (bool) $permissions->canEditTeamPackages,
                'canAddPackages' => (bool) $permissions->canAddPackages,
                'canCreateSubrepositories' => (bool) $permissions->canCreateSubrepositories,
                'canViewVendorCustomers' => (bool) $permissions->canViewVendorCustomers,
                'canManageVendorCustomers' => (bool) $permissions->canManageVendorCustomers,
            ],
            'canAccessAllPackages' => $canAccessAllPackages,
        ];

        return $this->post('/teams/', $parameters);
    }

    public function show($teamId)
    {
        return $this->get(sprintf('/teams/%s/', $teamId));
    }

    public function edit($teamId, string $name, TeamPermissions $permissions, ?bool $canAccessAllPackages = null): array
    {
        $parameters = [
            'name' => $name,
            'permissions' => [
                'canEditTeamPackages' => (bool) $permissions->canEditTeamPackages,
                'canAddPackages' => (bool) $permissions->canAddPackages,
                'canCreateSubrepositories' => (bool) $permissions->canCreateSubrepositories,
                'canViewVendorCustomers' => (bool) $permissions->canViewVendorCustomers,
                'canManageVendorCustomers' => (bool) $permissions->canManageVendorCustomers,
            ],
        ];
        if ($canAccessAllPackages !== null) {
            $parameters['canAccessAllPackages'] = $canAccessAllPackages;
        }

        return $this->put(sprintf('/teams/%s/', $teamId), $parameters);
    }

    public function remove($teamId): array
    {
        return $this->delete(sprintf('/teams/%s/', $teamId));
    }

    public function addMember($teamId, $userId): array
    {
        return $this->put(sprintf('/teams/%s/members/%s/', $teamId, $userId));
    }

    public function removeMember($teamId, $userId): array
    {
        return $this->delete(sprintf('/teams/%s/members/%s/', $teamId, $userId));
    }

    public function packages($teamId)
    {
        return $this->get(sprintf('/teams/%s/packages/', $teamId));
    }

    public function addPackages($teamId, array $packages)
    {
        return $this->post(sprintf('/teams/%s/packages/', $teamId), $packages);
    }

    public function removePackage($teamId, $packageName)
    {
        return $this->delete(sprintf('/teams/%s/packages/%s/', $teamId, $packageName));
    }
}
