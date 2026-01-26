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
use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;

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

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/subrepositories/project/packages/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
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

        $expectedQueryParams = [
            'origin' => \PrivatePackagist\ApiClient\Api\Packages::ORIGIN_PRIVATE,
            'limit' => AbstractApi::DEFAULT_LIMIT,
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/subrepositories/project/packages/'), $this->equalTo($expectedQueryParams))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all($projectName, $filters));
    }

    public function testAllWithInvalidFilters()
    {
        $this->expectException(InvalidArgumentException::class);

        $projectName = 'project';
        $filters = [
            'origin' => 'invalid'
        ];

        /** @var Packages&MockObject $api */
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

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/subrepositories/project/packages/acme-website/package/'))
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

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/subrepositories/project/packages/'), $this->equalTo(['repoType' => 'vcs', 'repoUrl' => 'localhost', 'credentials' => null]))
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

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/subrepositories/project/packages/'), $this->equalTo(['repoType' => 'package', 'repoConfig' => '{}', 'credentials' => null]))
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

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/subrepositories/project/packages/acme-website/package/'), $this->equalTo(['repoType' => 'vcs', 'repoUrl' => 'localhost', 'credentials' => null]))
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

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/subrepositories/project/packages/acme-website/package/'), $this->equalTo(['repoType' => 'package', 'repoConfig' => '{}', 'credentials' => null]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->editCustomPackage($projectName, 'acme-website/package', '{}'));
    }

    public function testRemove()
    {
        $projectName = 'project';
        $expected = [];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/subrepositories/project/packages/acme-website/package/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->remove($projectName, 'acme-website/package'));
    }

    protected function getApiClass()
    {
        return Packages::class;
    }
}
