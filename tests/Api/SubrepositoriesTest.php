<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use PHPUnit\Framework\MockObject\MockObject;

class SubrepositoriesTest extends ApiTestCase
{
    public function testAll()
    {
        $expected = [
            $this->getSubrepositoryDefinition(),
        ];

        /** @var Subrepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/subrepositories/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all());
    }

    public function testShow()
    {
        $expected = $this->getSubrepositoryDefinition();

        $subrepositoryName = 'subrepository';

        /** @var Subrepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/subrepositories/subrepository/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->show($subrepositoryName));
    }

    public function testCreate()
    {
        $expected = $this->getSubrepositoryDefinition();

        /** @var Subrepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/subrepositories/'), $this->equalTo(['name' => 'ACME Websites']))
            ->willReturn($expected);

        $this->assertSame($expected, $api->create('ACME Websites'));
    }

    public function testRemove()
    {
        $expected = '';

        /** @var Subrepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/subrepositories/subrepository/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->remove('subrepository'));
    }

    public function testListTeams()
    {
        $expected = [
            [
                'id' => 42,
                'name' => 'Owners',
                'permission' => 'owner',
                'members' => [],
                'subrepositories' => [],
            ],
        ];

        /** @var Subrepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/subrepositories/subrepository/teams/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->listTeams('subrepository'));
    }

    public function testAddOrEditTeam()
    {
        $expected = [
            [
                'id' => 42,
                'name' => 'Owners',
                'permission' => 'owner',
                'members' => [],
                'subrepositories' => [],
            ],
        ];

        $teams = [['id' => 42, 'permission' => 'owner']];

        /** @var Subrepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/subrepositories/subrepository/teams/'), $this->equalTo($teams))
            ->willReturn($expected);

        $this->assertSame($expected, $api->addOrEditTeams('subrepository', $teams));
    }

    public function testRemoveTeam()
    {
        $expected = '';

        /** @var Subrepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/subrepositories/subrepository/teams/42/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->removeTeam('subrepository', 42));
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

        /** @var Subrepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/subrepositories/subrepository/tokens/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->listTokens('subrepository'));
    }

    public function testCreateToken()
    {
        $expected = [
            'description' => 'Subrepository Token',
            'access' => 'read',
            'url' => 'https://vendor-org.repo.packagist.com/acme-websites/',
            'user' => 'token',
            'token' => 'password',
            'lastUsed' => '2018-03-14T11:36:00+00:00'
        ];

        /** @var Subrepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/subrepositories/subrepository/tokens/'), $this->equalTo([
                'description' => 'Subrepository Token',
                'access' => 'read',
            ]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->createToken('subrepository', [
            'description' => 'Subrepository Token',
            'access' => 'read',
        ]));
    }

    public function testRemoveToken()
    {
        $expected = [];

        /** @var Subrepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/subrepositories/subrepository/tokens/1/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->removeToken('subrepository', 1));
    }

    public function testRegenerateToken()
    {
        $expected = [];

        /** @var Subrepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/subrepositories/subrepository/tokens/1/regenerate'), $this->equalTo(['IConfirmOldTokenWillStopWorkingImmediately' => true]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->regenerateToken('subrepository', 1, ['IConfirmOldTokenWillStopWorkingImmediately' => true]));
    }

    private function getSubrepositoryDefinition()
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
        return Subrepositories::class;
    }
}
