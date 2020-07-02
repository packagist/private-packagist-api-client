<?php

/*
 * (c) Packagist Conductors UG (haftungsbeschränkt) <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

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

        /** @var Customers&\PHPUnit_Framework_MockObject_MockObject $api */
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

        /** @var Teams&\PHPUnit_Framework_MockObject_MockObject $api */
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

        /** @var Teams&\PHPUnit_Framework_MockObject_MockObject $api */
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

        /** @var Teams&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/teams/1/packages/acme-website/package/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->removePackage(1, 'acme-website/package'));
    }

    /**
     * @return string
     */
    protected function getApiClass()
    {
        return Teams::class;
    }
}
