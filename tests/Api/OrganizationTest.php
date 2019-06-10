<?php

/*
 * (c) Packagist Conductors UG (haftungsbeschränkt) <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

class OrganizationTest extends ApiTestCase
{
    public function testSync()
    {
        $expected = [];

        /** @var Organization&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/organization/sync'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->sync());
    }

    /**
     * @return string
     */
    protected function getApiClass()
    {
        return Organization::class;
    }
}
