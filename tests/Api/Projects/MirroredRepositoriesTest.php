<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api\Projects;

use PHPUnit\Framework\MockObject\MockObject;
use PrivatePackagist\ApiClient\Api\AbstractApi;
use PrivatePackagist\ApiClient\Api\ApiTestCase;

class MirroredRepositoriesTest extends ApiTestCase
{
    public function testAll()
    {
        $projectName = 'project';
        $expected = [
            $this->getProjectMirroredRepositoryDefinition(),
        ];

        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/subrepositories/project/mirrored-repositories/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all($projectName));
    }

    public function testShow()
    {
        $projectName = 'project';
        $expected = $this->getProjectMirroredRepositoryDefinition();

        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/subrepositories/project/mirrored-repositories/1/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->show($projectName, 1));
    }

    public function testAdd()
    {
        $projectName = 'project';
        $expected = $this->getProjectMirroredRepositoryDefinition();
        $data = [
            'id' => $expected['mirroredRepository']['id'],
            'mirroringBehavior' => $expected['mirroringBehavior'],
        ];

        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/subrepositories/project/mirrored-repositories/'), $this->equalTo([$data]))
            ->willReturn([$expected]);

        $this->assertSame([$expected], $api->add($projectName, [$data]));
    }

    public function testEdit()
    {
        $projectName = 'project';
        $expected = $this->getProjectMirroredRepositoryDefinition();
        $mirroredRepositoryId = $expected['mirroredRepository']['id'];
        $data = [
            'mirroringBehavior' => $mirroringBehaviour = $expected['mirroringBehavior'],
        ];

        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/subrepositories/project/mirrored-repositories/1/'), $this->equalTo($data))
            ->willReturn($expected);

        $this->assertSame($expected, $api->edit($projectName, $mirroredRepositoryId, $mirroringBehaviour));
    }
    public function testRemove()
    {
        $projectName = 'project';
        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/subrepositories/project/mirrored-repositories/1/'))
            ->willReturn([]);

        $this->assertSame([], $api->remove($projectName, 1));
    }

    public function testListPackages()
    {
        $projectName = 'project';
        $expected = [[
            'name' => 'acme/cool-lib',
            'origin' => 'public-mirror',
            'credentials' => null,
        ]];
        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/subrepositories/project/mirrored-repositories/1/packages/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->listPackages($projectName, 1));
    }

    public function testAddPackages()
    {
        $projectName = 'project';
        $expected = [[
            'id' => 'job-id',
            'status' => 'queued',
        ]];

        $packages = [
            'acme/cool-lib',
        ];

        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/subrepositories/project/mirrored-repositories/1/packages/'), $this->equalTo($packages))
            ->willReturn($expected);

        $this->assertSame($expected, $api->addPackages($projectName, 1, $packages));
    }

    public function testRemovePackages()
    {
        $projectName = 'project';
        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/subrepositories/project/mirrored-repositories/1/packages/'))
            ->willReturn([]);

        $this->assertSame([], $api->removePackages($projectName, 1));
    }

    protected function getApiClass()
    {
        return MirroredRepositories::class;
    }

    private function getProjectMirroredRepositoryDefinition()
    {
        return [
            'mirroringBehavior' => 'add_on_use',
            'mirroredRepository' => [
                'id' => 1,
                'name' => 'Packagist.org',
                'url' => 'https://packagist.org',
                'mirroringBehavior' => 'add_on_use',
                'credentials' => null,
            ]
        ];
    }
}
