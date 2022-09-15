<?php

/*
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

class Teams extends AbstractApi
{
    public function all()
    {
        return $this->get('/teams/');
    }

    public function create($name, $canEditTeamPackages = false, $canAddPackages = false, $canCreateSubrepositories = false, $canViewVendorCustomers = false, $canManageVendorCustomers = false)
    {
        $parameters = [
            'name' => $name,
            'permissions' => [
                'canEditTeamPackages' => $canEditTeamPackages,
                'canAddPackages' => $canAddPackages,
                'canCreateSubrepositories' => $canCreateSubrepositories,
                'canViewVendorCustomers' => $canViewVendorCustomers,
                'canManageVendorCustomers' => $canManageVendorCustomers,
            ],
        ];

        return $this->post('/teams/', $parameters);
    }

    public function edit($teamId, $name, $canEditTeamPackages, $canAddPackages, $canCreateSubrepositories, $canViewVendorCustomers, $canManageVendorCustomers)
    {
        $parameters = [
            'name' => $name,
            'permissions' => [
                'canEditTeamPackages' => $canEditTeamPackages,
                'canAddPackages' => $canAddPackages,
                'canCreateSubrepositories' => $canCreateSubrepositories,
                'canViewVendorCustomers' => $canViewVendorCustomers,
                'canManageVendorCustomers' => $canManageVendorCustomers,
            ],
        ];

        return $this->put(sprintf('/teams/%s/', $teamId), $parameters);
    }

    public function remove($teamId)
    {
        return $this->delete(sprintf('/teams/%s/', $teamId));
    }

    public function addMember($teamId, $userId)
    {
        return $this->put(sprintf('/teams/%s/members/%s/', $teamId, $userId));
    }

    public function removeMember($teamId, $userId)
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
