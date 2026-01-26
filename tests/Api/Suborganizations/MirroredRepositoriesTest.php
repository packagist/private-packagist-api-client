<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api\Suborganizations;

use PHPUnit\Framework\MockObject\MockObject;
use PrivatePackagist\ApiClient\Api\AbstractApi;
use PrivatePackagist\ApiClient\Api\ApiTestCase;

class MirroredRepositoriesTest extends ApiTestCase
{
    public function testAll()
    {
        $suborganizationName = 'suborganization';
        $expected = [
            $this->getProjectMirroredRepositoryDefinition(),
        ];

        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/suborganizations/suborganization/mirrored-repositories/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all($suborganizationName));
    }

    public function testShow()
    {
        $suborganizationName = 'suborganization';
        $expected = $this->getProjectMirroredRepositoryDefinition();

        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/suborganizations/suborganization/mirrored-repositories/1/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->show($suborganizationName, 1));
    }

    public function testAdd()
    {
        $suborganizationName = 'suborganization';
        $expected = $this->getProjectMirroredRepositoryDefinition();
        $data = [
            'id' => $expected['mirroredRepository']['id'],
            'mirroringBehavior' => $expected['mirroringBehavior'],
        ];

        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/suborganizations/suborganization/mirrored-repositories/'), $this->equalTo([$data]))
            ->willReturn([$expected]);

        $this->assertSame([$expected], $api->add($suborganizationName, [$data]));
    }

    public function testEdit()
    {
        $suborganizationName = 'suborganization';
        $expected = $this->getProjectMirroredRepositoryDefinition();
        $mirroredRepositoryId = $expected['mirroredRepository']['id'];
        $data = [
            'mirroringBehavior' => $mirroringBehaviour = $expected['mirroringBehavior'],
        ];

        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/suborganizations/suborganization/mirrored-repositories/1/'), $this->equalTo($data))
            ->willReturn($expected);

        $this->assertSame($expected, $api->edit($suborganizationName, $mirroredRepositoryId, $mirroringBehaviour));
    }
    public function testRemove()
    {
        $suborganizationName = 'suborganization';
        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/suborganizations/suborganization/mirrored-repositories/1/'))
            ->willReturn([]);

        $this->assertSame([], $api->remove($suborganizationName, 1));
    }

    public function testListPackages()
    {
        $suborganizationName = 'suborganization';
        $expected = [[
            'name' => 'acme/cool-lib',
            'origin' => 'public-mirror',
            'credentials' => null,
        ]];
        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/suborganizations/suborganization/mirrored-repositories/1/packages/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->listPackages($suborganizationName, 1));
    }

    public function testAddPackages()
    {
        $suborganizationName = 'suborganization';
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
            ->with($this->equalTo('/suborganizations/suborganization/mirrored-repositories/1/packages/'), $this->equalTo($packages))
            ->willReturn($expected);

        $this->assertSame($expected, $api->addPackages($suborganizationName, 1, $packages));
    }

    public function testRemovePackages()
    {
        $suborganizationName = 'suborganization';
        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/suborganizations/suborganization/mirrored-repositories/1/packages/'))
            ->willReturn([]);

        $this->assertSame([], $api->removePackages($suborganizationName, 1));
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
