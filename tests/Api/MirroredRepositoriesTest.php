<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use PHPUnit\Framework\MockObject\MockObject;

class MirroredRepositoriesTest extends ApiTestCase
{
    public function testAll()
    {
        $expected = [
            $this->getMirroredRepositoryDefinition(),
        ];

        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/mirrored-repositories/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all());
    }

    public function testShow()
    {
        $expected = $this->getMirroredRepositoryDefinition();

        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/mirrored-repositories/1/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->show(1));
    }

    public function testCreate()
    {
        $expected = $this->getMirroredRepositoryDefinition();
        $data = json_decode(json_encode($expected), true);
        unset($data['id']);

        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/mirrored-repositories/'), $this->equalTo($data))
            ->willReturn($expected);

        $this->assertSame($expected, $api->create($data['name'], $data['url'], $data['mirroringBehavior'], $data['credentials']));
    }

    public function testEdit()
    {
        $expected = $this->getMirroredRepositoryDefinition();
        $data = json_decode(json_encode($expected), true);
        unset($data['id']);

        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/mirrored-repositories/1/'), $this->equalTo($data))
            ->willReturn($expected);

        $this->assertSame($expected, $api->edit(1, $data['name'], $data['url'], $data['mirroringBehavior'], $data['credentials']));
    }
    public function testRemove()
    {
        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/mirrored-repositories/1/'))
            ->willReturn([]);

        $this->assertSame([], $api->remove(1));
    }

    public function testListPackages()
    {
        $expected = [[
            'name' => 'acme/cool-lib',
            'origin' => 'public-mirror',
            'credentials' => null,
        ]];
        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/mirrored-repositories/1/packages/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->listPackages(1));
    }

    public function testAddPackages()
    {
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
            ->with($this->equalTo('/mirrored-repositories/1/packages/'), $this->equalTo($packages))
            ->willReturn($expected);

        $this->assertSame($expected, $api->addPackages(1, $packages));
    }

    public function testRemovePackages()
    {
        /** @var MirroredRepositories&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/mirrored-repositories/1/packages/'))
            ->willReturn([]);

        $this->assertSame([], $api->removePackages(1));
    }

    protected function getApiClass()
    {
        return MirroredRepositories::class;
    }

    private function getMirroredRepositoryDefinition()
    {
        return [
            'id' => 1,
            'name' => 'Packagist.org',
            'url' => 'https://packagist.org',
            'mirroringBehavior' => 'add_on_use',
            'credentials' => null,
        ];
    }
}
