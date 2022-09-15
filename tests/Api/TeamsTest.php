<?php

/*
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use PHPUnit\Framework\MockObject\MockObject;

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
            ->with($this->equalTo('/teams/'))
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
            ->with($this->equalTo('/teams/1/packages/'))
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

    public function testCreateTeam()
    {
        $expected = [
            [
                'id' => 1,
                'name' => 'New Team',
                'permissions' => [
                    'canEditTeamPackages' => true,
                    'canAddPackages' => false,
                    'canCreateSubrepositories' => false,
                    'canViewVendorCustomers' => true,
                    'canManageVendorCustomers' => false,
                ],
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
                    'canCreateSubrepositories' => false,
                    'canViewVendorCustomers' => true,
                    'canManageVendorCustomers' => false,
                ],
            ]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->create('New Team', true, false, false, true, false));
    }

    public function testEditTeam()
    {
        $expected = [
            [
                'id' => 1,
                'name' => 'New Team',
                'permissions' => [
                    'canEditTeamPackages' => true,
                    'canAddPackages' => false,
                    'canCreateSubrepositories' => false,
                    'canViewVendorCustomers' => true,
                    'canManageVendorCustomers' => false,
                ],
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
                    'canCreateSubrepositories' => false,
                    'canViewVendorCustomers' => true,
                    'canManageVendorCustomers' => false,
                ],
            ]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->edit(123, 'New Team', true, false, false, true, false));
    }

    public function testDeleteTeam()
    {
        $expected = null;

        /** @var Teams&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/teams/1/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->remove(1));
    }

    public function testAddMember()
    {
        $expected = [
            [
                'id' => 1,
                'name' => 'New Team',
                'permission' => 'view',
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
            ->method('put')
            ->with($this->equalTo('/teams/1/members/12/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->addMember(1, 12));
    }

    public function removeMember()
    {
        $expected = [
            [
                'id' => 1,
                'name' => 'New Team',
                'permission' => 'view',
                'members' => [],
                'projects' => [
                ],
            ]
        ];

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
