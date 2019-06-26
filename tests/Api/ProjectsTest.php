<?php

/*
 * (c) Packagist Conductors UG (haftungsbeschränkt) <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

class ProjectsTest extends ApiTestCase
{
    public function testAll()
    {
        $expected = [
            $this->getProjectDefinition(),
        ];

        /** @var Projects&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/projects/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all());
    }

    public function testShow()
    {
        $expected = $this->getProjectDefinition();

        $projectName = 'project';

        /** @var Projects&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/projects/project/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->show($projectName));
    }

    public function testCreate()
    {
        $expected = $this->getProjectDefinition();

        /** @var Projects&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/projects/'), $this->equalTo(['name' => 'ACME Websites']))
            ->willReturn($expected);

        $this->assertSame($expected, $api->create('ACME Websites'));
    }

    public function testRemove()
    {
        $expected = '';

        /** @var Projects&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/projects/project/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->remove('project'));
    }

    public function testListTeams()
    {
        $expected = [
            [
                'id' => 42,
                'name' => 'Owners',
                'permission' => 'owner',
                'members' => [],
                'projects' => [],
            ],
        ];

        /** @var Projects&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/projects/project/teams/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->listTeams('project'));
    }

    public function testAddOrEditTeam()
    {
        $expected = [
            [
                'id' => 42,
                'name' => 'Owners',
                'permission' => 'owner',
                'members' => [],
                'projects' => [],
            ],
        ];

        $teams = [['id' => 42, 'permission' => 'owner']];

        /** @var Projects&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/projects/project/teams/'), $this->equalTo($teams))
            ->willReturn($expected);

        $this->assertSame($expected, $api->addOrEditTeams('project', $teams));
    }

    public function testRemoveTeam()
    {
        $expected = '';

        /** @var Projects&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/projects/project/teams/42/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->removeTeam('project', 42));
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

        /** @var Projects&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/projects/project/tokens/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->listTokens('project'));
    }

    public function testCreateToken()
    {
        $expected = [
            'description' => 'Project Token',
            'access' => 'read',
            'url' => 'https://vendor-org.repo.packagist.com/acme-websites/',
            'user' => 'token',
            'token' => 'password',
            'lastUsed' => '2018-03-14T11:36:00+00:00'
        ];

        /** @var Projects&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/projects/project/tokens/'), $this->equalTo([
                'description' => 'Project Token',
                'access' => 'read',
            ]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->createToken('project', [
            'description' => 'Project Token',
            'access' => 'read',
        ]));
    }

    public function testRemoveToken()
    {
        $expected = [];

        /** @var Projects&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/projects/project/tokens/1/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->removeToken('project', 1));
    }

    public function testRegenerateToken()
    {
        $expected = [];

        /** @var Projects&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/projects/project/tokens/1/regenerate'), $this->equalTo(['IConfirmOldTokenWillStopWorkingImmediately' => true]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->regenerateToken('project', 1, ['IConfirmOldTokenWillStopWorkingImmediately' => true]));
    }

    private function getProjectDefinition()
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
        return Projects::class;
    }
}
