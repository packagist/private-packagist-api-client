<?php

/*
 * (c) Packagist Conductors UG (haftungsbeschränkt) <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

class SecurityIssuesTest extends ApiTestCase
{
    public function testAll()
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

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/security-issues/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all());
    }

    public function testAllWithFilters()
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

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/security-issues/'), $this->equalTo($filters))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all($filters));
    }

    protected function getApiClass()
    {
        return SecurityIssues::class;
    }
}
