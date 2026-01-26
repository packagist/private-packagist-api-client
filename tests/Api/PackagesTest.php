<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use PHPUnit\Framework\MockObject\MockObject;
use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;

class PackagesTest extends ApiTestCase
{
    public function testAll()
    {
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
            ->with($this->equalTo('/packages/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all());
    }

    public function testAllWithFilters()
    {
        $expected = [
            [
                'id' => 1,
                'name' => 'acme-website/package',
            ],
        ];

        $filters = [
            'origin' => Packages::ORIGIN_PRIVATE,
        ];

        $expectedQueryParams = [
            'origin' => Packages::ORIGIN_PRIVATE,
            'limit' => AbstractApi::DEFAULT_LIMIT,
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('getCollection')
            ->with($this->equalTo('/packages/'), $this->equalTo($expectedQueryParams))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all($filters));
    }

    public function testAllWithInvalidFilters()
    {
        $this->expectException(InvalidArgumentException::class);

        $filters = [
            'origin' => 'invalid'
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->never())
            ->method('get');

        $api->all($filters);
    }

    public function testShow()
    {
        $expected = [
            'id' => 1,
            'name' => 'acme-website/package',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/packages/acme-website/package/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->show('acme-website/package'));
    }

    public function testCreateVcsPackage()
    {
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/packages/'), $this->equalTo(['repoType' => 'vcs', 'repoUrl' => 'localhost', 'credentials' => null]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->createVcsPackage('localhost'));
    }

    public function testCreateVcsPackageWithDefaultSubrepositoryAccess()
    {
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/packages/'), $this->equalTo(['repoType' => 'vcs', 'repoUrl' => 'localhost', 'credentials' => null, 'defaultSubrepositoryAccess' => 'no-access']))
            ->willReturn($expected);

        $this->assertSame($expected, $api->createVcsPackage('localhost', null, 'vcs', 'no-access'));
    }

    /**
     * @dataProvider customProvider
     */
    public function testCreateCustomPackage($customJson, $defaultSubrepositoryAccess, array $expectedPayload)
    {
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/packages/'), $this->equalTo($expectedPayload))
            ->willReturn($expected);

        $this->assertSame($expected, $api->createCustomPackage($customJson, null, $defaultSubrepositoryAccess));
    }

    public function customProvider()
    {
        return [
            ['{}', null, ['repoType' => 'package', 'repoConfig' => '{}', 'credentials' => null]],
            [new \stdClass(), null, ['repoType' => 'package', 'repoConfig' => '{}', 'credentials' => null]],
            [[], null, ['repoType' => 'package', 'repoConfig' => '[]', 'credentials' => null]],
            ['{}', 'no-access', ['repoType' => 'package', 'repoConfig' => '{}', 'credentials' => null, 'defaultSubrepositoryAccess' => 'no-access']],
        ];
    }

    public function testCreateArtifactPackageWithDefaultSubrepositoryAccess()
    {
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/packages/'), $this->equalTo(['repoType' => 'artifact', 'artifactIds' => [42], 'defaultSubrepositoryAccess' => 'no-access']))
            ->willReturn($expected);

        $this->assertSame($expected, $api->createArtifactPackage([42], 'no-access'));
    }

    public function testCreateArtifactPackage()
    {
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/packages/'), $this->equalTo(['repoType' => 'artifact', 'artifactIds' => [42]]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->createArtifactPackage([42]));
    }

    public function testEditVcsPackage()
    {
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/packages/acme-website/package/'), $this->equalTo(['repoType' => 'vcs', 'repoUrl' => 'localhost', 'credentials' => null]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->editVcsPackage('acme-website/package', 'localhost'));
    }

    public function testEditCustomPackage()
    {
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/packages/acme-website/package/'), $this->equalTo(['repoType' => 'package', 'repoConfig' => '{}', 'credentials' => null]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->editCustomPackage('acme-website/package', '{}'));
    }

    public function testEditArtifactPackage()
    {
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/packages/acme-website/package/'), $this->equalTo(['repoType' => 'artifact', 'artifactIds' => [1, 3]]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->editArtifactPackage('acme-website/package', [1, 3]));
    }

    public function testRemove()
    {
        $expected = [];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/packages/acme-website/package/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->remove('acme-website/package'));
    }

    public function testListPackages()
    {
        $expected = [
            [
                'package' => [
                    'name' => 'composer/composer',
                    'origin' => 'private',
                    'versionConstraint' => null,
                    'expirationDate' => null,
                ],
                'customer' => [
                    'id' => 1,
                    'name' => 'Customer',
                ],
            ]
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/packages/composer/composer/customers/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->listCustomers('composer/composer'));
    }

    public function testListDependents()
    {
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
            ->with($this->equalTo('/packages/acme-website/core-package/dependents/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->listDependents($packageName));
    }

    public function testListSecurityIssues()
    {
        $packageName = 'acme-website/core-package';
        $expected = [
            [
                'packageName' => 'acme-website/package',
                'state' => 'open',
                'branch' => 'dev-master',
                'installedPackage' => 'acme/library',
                'installedVersion' => '1.0.0',
                'advisory' => [
                    'title' => 'CVE-1999: Remote code execution',
                    'link' =>'https://acme.website/security-advisories',
                    'cve' => 'CVE-1999',
                    'affectedVersions' => '>=1.0',
                ],
            ],
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/packages/acme-website/core-package/security-issues/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->listSecurityIssues($packageName));
    }

    public function testShowSecurityMonitoringConfig()
    {
        $packageName = 'acme-website/core-package';
        $expected = [
            "monitorAllBranches" => false,
            "monitoredBranches" => [
                "dev-main"
            ],
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/packages/acme-website/core-package/security-monitoring/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->showSecurityMonitoringConfig($packageName));
    }

    public function testEditSecurityMonitoringConfig()
    {
        $packageName = 'acme-website/core-package';

        $editedConfig = [
            "monitorAllBranches" => false,
            "monitoredBranches" => [
                "dev-main"
            ],
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/packages/acme-website/core-package/security-monitoring/'), $this->equalTo($editedConfig))
            ->willReturn($editedConfig);

        $this->assertSame($editedConfig, $api->editSecurityMonitoringConfig($packageName, $editedConfig));
    }

    protected function getApiClass()
    {
        return Packages::class;
    }
}
