<?php

/*
 * (c) Packagist Conductors UG (haftungsbeschränkt) <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api\Projects;

use PrivatePackagist\ApiClient\Api\ApiTestCase;

class PackagesTest extends ApiTestCase
{
    public function testAll()
    {
        $projectName = 'project';
        $expected = [
            [
                'id' => 1,
                'name' => 'acme-website/package',
            ],
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/projects/project/packages/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all($projectName));
    }

    public function testAllWithFilters()
    {
        $projectName = 'project';
        $expected = [
            [
                'id' => 1,
                'name' => 'acme-website/package',
            ],
        ];

        $filters = [
            'origin' => \PrivatePackagist\ApiClient\Api\Packages::ORIGIN_PRIVATE,
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/projects/project/packages/'), $this->equalTo($filters))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all($projectName, $filters));
    }

    /**
     * @expectedException \PrivatePackagist\ApiClient\Exception\InvalidArgumentException
     */
    public function testAllWithInvalidFilters()
    {
        $projectName = 'project';
        $filters = [
            'origin' => 'invalid'
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->never())
            ->method('get');

        $api->all($projectName, $filters);
    }

    public function testShow()
    {
        $projectName = 'project';
        $expected = [
            'id' => 1,
            'name' => 'acme-website/package',
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/projects/project/packages/acme-website/package'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->show($projectName, 'acme-website/package'));
    }

    public function testCreateVcsPackage()
    {
        $projectName = 'project';
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/projects/project/packages/'), $this->equalTo(['repoType' => 'vcs', 'repoUrl' => 'localhost', 'credentials' => null]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->createVcsPackage($projectName, 'localhost'));
    }

    /**
     * @dataProvider customProvider
     */
    public function testCreateCustomPackage($customJson)
    {
        $projectName = 'project';
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/projects/project/packages/'), $this->equalTo(['repoType' => 'package', 'repoConfig' => '{}', 'credentials' => null]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->createCustomPackage($projectName, $customJson));
    }

    public function customProvider()
    {
        return [
            ['{}'],
            [new \stdClass()],
        ];
    }

    public function testEditVcsPackage()
    {
        $projectName = 'project';
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/projects/project/packages/acme-website/package/'), $this->equalTo(['repoType' => 'vcs', 'repoUrl' => 'localhost', 'credentials' => null]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->editVcsPackage($projectName, 'acme-website/package', 'localhost'));
    }

    public function testEditCustomPackage()
    {
        $projectName = 'project';
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/projects/project/packages/acme-website/package/'), $this->equalTo(['repoType' => 'package', 'repoConfig' => '{}', 'credentials' => null]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->editCustomPackage($projectName, 'acme-website/package', '{}'));
    }

    public function testRemove()
    {
        $projectName = 'project';
        $expected = [];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/projects/project/packages/acme-website/package/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->remove($projectName, 'acme-website/package'));
    }

    protected function getApiClass()
    {
        return Packages::class;
    }
}
