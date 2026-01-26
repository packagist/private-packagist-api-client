<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use PHPUnit\Framework\MockObject\MockObject;
use PrivatePackagist\ApiClient\TeamPermissions;

class TeamsTest extends ApiTestCase
{
    public function testAll()
    {
        $expected = [
            [
                'id' => 1,
                'name' => 'Owners',
                'permission' => 'owner',
                'members' => [
                    [
                        'id' => 12,
                        'username' => 'username'
                    ]
                ],
                'projects' => [
                ],
            ]
        ];

        /** @var Teams&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/teams/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all());
    }

    public function testPackages()
    {
        $expected = [
            [
                'id' => 1,
                'name' => 'acme-website/package',
            ],
        ];

        /** @var Teams&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/teams/1/packages/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->packages(1));
    }

    public function testAddPackages()
    {
        $expected = [
            [
                'id' => 1,
                'name' => 'acme-website/package',
            ],
        ];

        /** @var Teams&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/teams/1/packages/'), $this->equalTo(['acme-website/package']))
            ->willReturn($expected);

        $this->assertSame($expected, $api->addPackages(1, ['acme-website/package']));
    }

    public function testRemovePackage()
    {
        $expected = [];

        /** @var Teams&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/teams/1/packages/acme-website/package/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->removePackage(1, 'acme-website/package'));
    }

    public function testCreateTeam(): void
    {
        $expected = [
            'id' => 1,
            'name' => 'New Team',
            'permissions' => [
                'canEditTeamPackages' => true,
                'canAddPackages' => false,
                'canCreateSuborganizations' => false,
                'canViewVendorCustomers' => true,
                'canManageVendorCustomers' => false,
            ],
        ];

        /** @var Teams&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/teams/'), $this->equalTo([
                'name' => 'New Team',
                'permissions' => [
                    'canEditTeamPackages' => true,
                    'canAddPackages' => false,
                    'canCreateSuborganizations' => false,
                    'canViewVendorCustomers' => true,
                    'canManageVendorCustomers' => false,
                ],
            ]))
            ->willReturn($expected);

        $permissions = new TeamPermissions;
        $permissions->canEditTeamPackages = true;
        $permissions->canViewVendorCustomers = true;
        $this->assertSame($expected, $api->create('New Team', $permissions));
    }

    public function testShowTeam(): void
    {
        $expected = [
            'id' => 1,
            'name' => 'New Team',
            'permissions' => [
                'canEditTeamPackages' => true,
                'canAddPackages' => false,
                'canCreateSuborganizations' => false,
                'canViewVendorCustomers' => true,
                'canManageVendorCustomers' => false,
            ],
        ];

        /** @var Teams&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/teams/123/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->show(123));
    }

    public function testEditTeam(): void
    {
        $expected = [
            'id' => 123,
            'name' => 'New Team',
            'permissions' => [
                'canEditTeamPackages' => true,
                'canAddPackages' => false,
                'canCreateSuborganizations' => false,
                'canViewVendorCustomers' => true,
                'canManageVendorCustomers' => false,
            ],
        ];

        /** @var Teams&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/teams/123/'), $this->equalTo([
                'name' => 'New Team',
                'permissions' => [
                    'canEditTeamPackages' => true,
                    'canAddPackages' => false,
                    'canCreateSuborganizations' => false,
                    'canViewVendorCustomers' => true,
                    'canManageVendorCustomers' => false,
                ],
            ]))
            ->willReturn($expected);

        $permissions = new TeamPermissions;
        $permissions->canEditTeamPackages = true;
        $permissions->canViewVendorCustomers = true;
        $this->assertSame($expected, $api->edit(123, 'New Team', $permissions));
    }

    public function testTeamGrant(): void
    {
        $expected = [
            'id' => 123,
            'name' => 'New Team',
            'permissions' => [
                'canEditTeamPackages' => true,
                'canAddPackages' => false,
                'canCreateSuborganizations' => false,
                'canViewVendorCustomers' => true,
                'canManageVendorCustomers' => false,
            ],
        ];

        /** @var Teams&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/teams/123/all-package-access/grant'), $this->equalTo([]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->grantAccessToAllPackages(123));
    }

    public function testTeamRevoke(): void
    {
        $expected = [
            'id' => 123,
            'name' => 'New Team',
            'permissions' => [
                'canEditTeamPackages' => true,
                'canAddPackages' => false,
                'canCreateSuborganizations' => false,
                'canViewVendorCustomers' => true,
                'canManageVendorCustomers' => false,
            ],
        ];

        /** @var Teams&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/teams/123/all-package-access/revoke'), $this->equalTo([]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->revokeAccessToAllPackages(123));
    }

    public function testDeleteTeam(): void
    {
        $expected = [];

        /** @var Teams&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/teams/1/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->remove(1));
    }

    public function testAddMember(): void
    {
        $expected = [
            'id' => 1,
            'name' => 'New Team',
            'members' => [
                [
                    'id' => 12,
                    'username' => 'username'
                ]
            ],
        ];

        /** @var Teams&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/teams/1/members/12/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->addMember(1, 12));
    }

    public function removeMember(): void
    {
        $expected = [];

        /** @var Teams&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/teams/1/members/12/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->removeMember(1, 12));
    }

    /**
     * @return string
     */
    protected function getApiClass()
    {
        return Teams::class;
    }
}
