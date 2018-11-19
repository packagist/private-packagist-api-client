<?php

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
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->all());
    }

    public function testShow()
    {
        $expected = $this->getProjectDefinition();

        $projectId = 1;

        /** @var Projects&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/projects/1/'))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->show($projectId));
    }

    public function testCreate()
    {
        $expected = $this->getProjectDefinition();

        /** @var Projects&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/projects/'), $this->equalTo(['name' => 'ACME Websites']))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->create('ACME Websites'));
    }

    public function testRemove()
    {
        $expected = '';

        /** @var Projects&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/projects/1/'))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->remove(1));
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
            ->with($this->equalTo('/projects/1/teams/'))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->listTeams(1));
    }

    public function testAddOrUpdateTeam()
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
            ->with($this->equalTo('/projects/1/teams/'), $this->equalTo($teams))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->addOrUpdateTeams(1, $teams));
    }

    public function testRemoveTeam()
    {
        $expected = '';

        /** @var Projects&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/projects/1/teams/42/'))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->removeTeam(1, 42));
    }

    public function testListPackages()
    {
        $expected = [
            [
                'name' => 'composer/composer',
                'origin' => 'private',
                'versionConstraint' => null,
                'expirationDate' => null,
            ],
        ];

        /** @var Projects&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/projects/1/packages/'))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->listPackages(1));
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
