<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api\Suborganizations;

use PHPUnit\Framework\MockObject\MockObject;
use PrivatePackagist\ApiClient\Api\ApiTestCase;
use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;

class PackagesTest extends ApiTestCase
{
    public function testAll()
    {
        $suborganizationName = 'suborganization';
        $expected = [
            [
                'id' => 1,
                'name' => 'acme-website/package',
            ],
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('getCollection')
            ->with($this->equalTo('/suborganizations/suborganization/packages/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all($suborganizationName));
    }

    public function testAllWithFilters()
    {
        $suborganizationName = 'suborganization';
        $expected = [
            [
                'id' => 1,
                'name' => 'acme-website/package',
            ],
        ];

        $filters = [
            'origin' => \PrivatePackagist\ApiClient\Api\Packages::ORIGIN_PRIVATE,
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('getCollection')
            ->with($this->equalTo('/suborganizations/suborganization/packages/'), $this->equalTo($filters))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all($suborganizationName, $filters));
    }

    public function testAllWithInvalidFilters()
    {
        $this->expectException(InvalidArgumentException::class);

        $suborganizationName = 'suborganization';
        $filters = [
            'origin' => 'invalid'
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->never())
            ->method('get');

        $api->all($suborganizationName, $filters);
    }

    public function testShow()
    {
        $suborganizationName = 'suborganization';
        $expected = [
            'id' => 1,
            'name' => 'acme-website/package',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/suborganizations/suborganization/packages/acme-website/package/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->show($suborganizationName, 'acme-website/package'));
    }

    public function testCreateVcsPackage()
    {
        $suborganizationName = 'suborganization';
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/suborganizations/suborganization/packages/'), $this->equalTo(['repoType' => 'vcs', 'repoUrl' => 'localhost', 'credentials' => null]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->createVcsPackage($suborganizationName, 'localhost'));
    }

    /**
     * @dataProvider customProvider
     */
    public function testCreateCustomPackage($customJson)
    {
        $suborganizationName = 'suborganization';
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/suborganizations/suborganization/packages/'), $this->equalTo(['repoType' => 'package', 'repoConfig' => '{}', 'credentials' => null]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->createCustomPackage($suborganizationName, $customJson));
    }

    public function customProvider()
    {
        return [
            ['{}'],
            [new \stdClass()],
        ];
    }

    public function testCreateArtifactPackage()
    {
        $suborganizationName = 'suborganization';
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/suborganizations/suborganization/packages/'), $this->equalTo(['repoType' => 'artifact', 'artifactIds' => [42]]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->createArtifactPackage($suborganizationName, [42]));
    }

    public function testCreateArtifactPackageWithDefaultSubrepositoryAccess()
    {
        $suborganizationName = 'suborganization';
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/suborganizations/suborganization/packages/'), $this->equalTo(['repoType' => 'artifact', 'artifactIds' => [42], 'defaultSubrepositoryAccess' => 'no-access']))
            ->willReturn($expected);

        $this->assertSame($expected, $api->createArtifactPackage($suborganizationName, [42], 'no-access'));
    }

    public function testEditVcsPackage()
    {
        $suborganizationName = 'suborganization';
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/suborganizations/suborganization/packages/acme-website/package/'), $this->equalTo(['repoType' => 'vcs', 'repoUrl' => 'localhost', 'credentials' => null]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->editVcsPackage($suborganizationName, 'acme-website/package', 'localhost'));
    }

    public function testEditCustomPackage()
    {
        $suborganizationName = 'suborganization';
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/suborganizations/suborganization/packages/acme-website/package/'), $this->equalTo(['repoType' => 'package', 'repoConfig' => '{}', 'credentials' => null]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->editCustomPackage($suborganizationName, 'acme-website/package', '{}'));
    }

    public function testEditArtifactPackage()
    {
        $suborganizationName = 'suborganization';
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/suborganizations/suborganization/packages/acme-website/package/'), $this->equalTo(['repoType' => 'artifact', 'artifactIds' => [1, 3]]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->editArtifactPackage($suborganizationName, 'acme-website/package', [1, 3]));
    }

    public function testRemove()
    {
        $suborganizationName = 'suborganization';
        $expected = [];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/suborganizations/suborganization/packages/acme-website/package/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->remove($suborganizationName, 'acme-website/package'));
    }

    public function testListDependents()
    {
        $suborganizationName = 'suborganization';
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
            ->method('getCollection')
            ->with($this->equalTo('/suborganizations/suborganization/packages/acme-website/core-package/dependents/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->listDependents($suborganizationName, $packageName));
    }

    protected function getApiClass()
    {
        return Packages::class;
    }
}
