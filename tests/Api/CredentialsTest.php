<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use PHPUnit\Framework\MockObject\MockObject;

class CredentialsTest extends ApiTestCase
{
    public function testAll()
    {
        $expected = [
            [
                'id' => 1,
                'description' => 'My secret credential',
                'domain' => 'localhost',
                'username' => 'username',
                'credential' => 'password',
                'type' => 'http-basic',
            ],
        ];

        /** @var Credentials&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/credentials/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all());
    }

    public function testShow()
    {
        $expected = [
            'id' => 1,
            'description' => 'My secret credential',
            'domain' => 'localhost',
            'username' => 'username',
            'credential' => 'password',
            'type' => 'http-basic',
        ];

        /** @var Credentials&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/credentials/1/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->show(1));
    }

    public function testCreate()
    {
        $expected = [
            'id' => 1,
            'description' => 'My secret credential',
            'domain' => 'localhost',
            'username' => 'username',
            'credential' => 'password',
            'type' => 'http-basic',
        ];

        /** @var Credentials&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/credentials/'), $this->equalTo(['domain' => 'localhost', 'description' => 'My secret credential', 'type' => 'http-basic', 'username' => 'username', 'credential' => 'password']))
            ->willReturn($expected);

        $this->assertSame($expected, $api->create('My secret credential', 'http-basic', 'localhost', 'username', 'password'));
    }

    public function testEdit()
    {
        $expected = [
            'id' => 1,
            'description' => 'My secret credential',
            'domain' => 'localhost',
            'username' => 'username',
            'credential' => 'password',
            'type' => 'http-basic',
        ];

        /** @var Credentials&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/credentials/1/'), $this->equalTo(['type' => 'http-basic', 'username' => 'username', 'credential' => 'password']))
            ->willReturn($expected);

        $this->assertSame($expected, $api->edit(1, 'http-basic', 'username', 'password'));
    }

    public function testRemove()
    {
        $expected = [];

        /** @var Credentials&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/credentials/1/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->remove(1));
    }

    /**
     * @return string
     */
    protected function getApiClass()
    {
        return Credentials::class;
    }
}
