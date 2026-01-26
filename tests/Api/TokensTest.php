<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use PHPUnit\Framework\MockObject\MockObject;

class TokensTest extends ApiTestCase
{
    public function testAll()
    {
        $expected = [
            [
                'description' => 'Team Token',
                'access' => 'read',
                'teamId' => 1,
                'url' => 'https://repo.packagist.com/acme-websites/',
                'user' => 'token',
                'token' => 'password',
                'lastUsed' => '2018-03-14T11:36:00+00:00'
            ],
        ];

        /** @var Tokens&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/tokens/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all());
    }

    public function testCreate()
    {
        $expected = [
            'description' => 'Team Token',
            'access' => 'read',
            'teamId' => 1,
            'url' => 'https://repo.packagist.com/acme-websites/',
            'user' => 'token',
            'token' => 'password',
            'lastUsed' => '2018-03-14T11:36:00+00:00'
        ];

        /** @var Tokens&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/tokens/'), $this->equalTo([
                'description' => 'Team Token',
                'access' => 'read',
                'teamId' => 1,
            ]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->create([
            'description' => 'Team Token',
            'access' => 'read',
            'teamId' => 1,
        ]));
    }

    public function testCreateTeamIdAndAllAccess()
    {
        $this->expectException(\InvalidArgumentException::class);
        /** @var Tokens&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->never())
            ->method('post');

        $api->create([
            'description' => 'Team Token',
            'access' => 'read',
            'teamId' => 1,
            'accessToAllPackages' => true,
        ]);
    }

    public function testRemove()
    {
        $expected = [];

        /** @var Tokens&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/tokens/1/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->remove(1));
    }

    public function testRegenerateToken()
    {
        $expected = [];

        /** @var Tokens&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/tokens/1/regenerate'), $this->equalTo(['IConfirmOldTokenWillStopWorkingImmediately' => true]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->regenerate(1, ['IConfirmOldTokenWillStopWorkingImmediately' => true]));
    }

    /**
     * @return string
     */
    protected function getApiClass()
    {
        return Tokens::class;
    }
}
