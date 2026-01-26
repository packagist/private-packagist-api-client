<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use PHPUnit\Framework\MockObject\MockObject;

class SuborganizationsTest extends ApiTestCase
{
    public function testAll()
    {
        $expected = [
            $this->getSuborganizationDefinition(),
        ];

        /** @var Suborganizations&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/suborganizations/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all());
    }

    public function testShow()
    {
        $expected = $this->getSuborganizationDefinition();

        $suborganizationName = 'suborganization';

        /** @var Suborganizations&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/suborganizations/suborganization/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->show($suborganizationName));
    }

    public function testCreate()
    {
        $expected = $this->getSuborganizationDefinition();

        /** @var Suborganizations&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/suborganizations/'), $this->equalTo(['name' => 'ACME Websites']))
            ->willReturn($expected);

        $this->assertSame($expected, $api->create('ACME Websites'));
    }

    public function testRemove()
    {
        $expected = '';

        /** @var Suborganizations&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/suborganizations/suborganization/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->remove('suborganization'));
    }

    public function testListTeams()
    {
        $expected = [
            [
                'id' => 42,
                'name' => 'Owners',
                'permission' => 'owner',
                'members' => [],
                'suborganizations' => [],
            ],
        ];

        /** @var Suborganizations&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/suborganizations/suborganization/teams/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->listTeams('suborganization'));
    }

    public function testAddOrEditTeam()
    {
        $expected = [
            [
                'id' => 42,
                'name' => 'Owners',
                'permission' => 'owner',
                'members' => [],
                'suborganizations' => [],
            ],
        ];

        $teams = [['id' => 42, 'permission' => 'owner']];

        /** @var Suborganizations&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/suborganizations/suborganization/teams/'), $this->equalTo($teams))
            ->willReturn($expected);

        $this->assertSame($expected, $api->addOrEditTeams('suborganization', $teams));
    }

    public function testRemoveTeam()
    {
        $expected = '';

        /** @var Suborganizations&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/suborganizations/suborganization/teams/42/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->removeTeam('suborganization', 42));
    }

    public function testListTokens()
    {
        $expected = [
            [
                'description' => 'Generated Client Token',
                'access' => 'read',
                'url' => 'https://vendor-org.repo.packagist.com/acme-websites/',
                'user' => 'token',
                'token' => 'password',
                'lastUsed' => '2018-03-14T11:36:00+00:00'
            ],
        ];

        /** @var Suborganizations&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/suborganizations/suborganization/tokens/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->listTokens('suborganization'));
    }

    public function testCreateToken()
    {
        $expected = [
            'description' => 'Suborganization Token',
            'access' => 'read',
            'url' => 'https://vendor-org.repo.packagist.com/acme-websites/',
            'user' => 'token',
            'token' => 'password',
            'lastUsed' => '2018-03-14T11:36:00+00:00'
        ];

        /** @var Suborganizations&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/suborganizations/suborganization/tokens/'), $this->equalTo([
                'description' => 'Suborganization Token',
                'access' => 'read',
            ]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->createToken('suborganization', [
            'description' => 'Suborganization Token',
            'access' => 'read',
        ]));
    }

    public function testRemoveToken()
    {
        $expected = [];

        /** @var Suborganizations&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/suborganizations/suborganization/tokens/1/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->removeToken('suborganization', 1));
    }

    public function testRegenerateToken()
    {
        $expected = [];

        /** @var Suborganizations&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/suborganizations/suborganization/tokens/1/regenerate'), $this->equalTo(['IConfirmOldTokenWillStopWorkingImmediately' => true]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->regenerateToken('suborganization', 1, ['IConfirmOldTokenWillStopWorkingImmediately' => true]));
    }

    private function getSuborganizationDefinition()
    {
        return [
            'id' => 1,
            'name' => 'ACME Websites',
            'urlName' => 'acme-websites',
            'teams' => [
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
                ]
            ]
        ];
    }

    /**
     * @return string
     */
    protected function getApiClass()
    {
        return Suborganizations::class;
    }
}
