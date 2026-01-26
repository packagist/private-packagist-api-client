<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use PHPUnit\Framework\MockObject\MockObject;

class SecurityIssuesTest extends ApiTestCase
{
    public function testAll(): void
    {
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

        /** @var SecurityIssues&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/security-issues/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all());
    }

    public function testAllWithFilters(): void
    {
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

        $filters = [
            'security-issue-state' => SecurityIssues::STATE_OPEN,
        ];

        $expectedQueryParams = [
            'security-issue-state' => SecurityIssues::STATE_OPEN,
            'limit' => AbstractApi::DEFAULT_LIMIT,
        ];

        /** @var SecurityIssues&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/security-issues/'), $this->equalTo($expectedQueryParams))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all($filters));
    }

    public function testShow(): void
    {
        $expected = [
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
        ];

        /** @var SecurityIssues&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/security-issues/12'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->show(12));
    }

    public function testOpen(): void
    {
        $expected = [
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
        ];

        /** @var SecurityIssues&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/security-issues/12/open'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->open(12));
    }

    public function testClose(): void
    {
        $expected = [
            'packageName' => 'acme-website/package',
            'state' => SecurityIssues::STATE_IN_PROGRESS,
            'branch' => 'dev-master',
            'installedPackage' => 'acme/library',
            'installedVersion' => '1.0.0',
            'advisory' => [
                'title' => 'CVE-1999: Remote code execution',
                'link' =>'https://acme.website/security-advisories',
                'cve' => 'CVE-1999',
                'affectedVersions' => '>=1.0',
            ],
        ];

        /** @var SecurityIssues&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/security-issues/12/close/' . SecurityIssues::STATE_IN_PROGRESS))
            ->willReturn($expected);

        $this->assertSame($expected, $api->close(12, SecurityIssues::STATE_IN_PROGRESS));
    }

    protected function getApiClass(): string
    {
        return SecurityIssues::class;
    }
}
