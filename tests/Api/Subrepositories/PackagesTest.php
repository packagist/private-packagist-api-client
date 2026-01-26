<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api\Subrepositories;

use PHPUnit\Framework\MockObject\MockObject;
use PrivatePackagist\ApiClient\Api\AbstractApi;
use PrivatePackagist\ApiClient\Api\ApiTestCase;
use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;

class PackagesTest extends ApiTestCase
{
    public function testAll()
    {
        $subrepositoryName = 'subrepository';
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
            ->with($this->equalTo('/subrepositories/subrepository/packages/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all($subrepositoryName));
    }

    public function testAllWithFilters()
    {
        $subrepositoryName = 'subrepository';
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
            ->with($this->equalTo('/subrepositories/subrepository/packages/'), $this->equalTo($expectedQueryParams))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all($subrepositoryName, $filters));
    }

    public function testAllWithInvalidFilters()
    {
        $this->expectException(InvalidArgumentException::class);

        $subrepositoryName = 'subrepository';
        $filters = [
            'origin' => 'invalid'
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->never())
            ->method('get');

        $api->all($subrepositoryName, $filters);
    }

    public function testShow()
    {
        $subrepositoryName = 'subrepository';
        $expected = [
            'id' => 1,
            'name' => 'acme-website/package',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/subrepositories/subrepository/packages/acme-website/package/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->show($subrepositoryName, 'acme-website/package'));
    }

    public function testCreateVcsPackage()
    {
        $subrepositoryName = 'subrepository';
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/subrepositories/subrepository/packages/'), $this->equalTo(['repoType' => 'vcs', 'repoUrl' => 'localhost', 'credentials' => null]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->createVcsPackage($subrepositoryName, 'localhost'));
    }

    /**
     * @dataProvider customProvider
     */
    public function testCreateCustomPackage($customJson)
    {
        $subrepositoryName = 'subrepository';
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/subrepositories/subrepository/packages/'), $this->equalTo(['repoType' => 'package', 'repoConfig' => '{}', 'credentials' => null]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->createCustomPackage($subrepositoryName, $customJson));
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
        $subrepositoryName = 'subrepository';
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/subrepositories/subrepository/packages/acme-website/package/'), $this->equalTo(['repoType' => 'vcs', 'repoUrl' => 'localhost', 'credentials' => null]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->editVcsPackage($subrepositoryName, 'acme-website/package', 'localhost'));
    }

    public function testEditCustomPackage()
    {
        $subrepositoryName = 'subrepository';
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/subrepositories/subrepository/packages/acme-website/package/'), $this->equalTo(['repoType' => 'package', 'repoConfig' => '{}', 'credentials' => null]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->editCustomPackage($subrepositoryName, 'acme-website/package', '{}'));
    }

    public function testRemove()
    {
        $subrepositoryName = 'subrepository';
        $expected = [];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/subrepositories/subrepository/packages/acme-website/package/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->remove($subrepositoryName, 'acme-website/package'));
    }

    public function testListDependents()
    {
        $subrepositoryName = 'subrepository';
        $packageName = 'acme-website/core-package';
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
            ->with($this->equalTo('/subrepositories/subrepository/packages/acme-website/core-package/dependents/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->listDependents($subrepositoryName, $packageName));
    }

    protected function getApiClass()
    {
        return Packages::class;
    }
}
