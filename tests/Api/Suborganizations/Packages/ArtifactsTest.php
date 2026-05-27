<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api\Suborganizations\Packages;

use PHPUnit\Framework\MockObject\MockObject;
use PrivatePackagist\ApiClient\Api\ApiTestCase;

class ArtifactsTest extends ApiTestCase
{
    public function testCreate()
    {
        $suborganizationName = 'suborganization';
        $expected = [
            'id' => 1,
        ];
        $rawFileContent = 'foobar';
        $headers = [
            'Content-Type' => 'application/zip',
            'X-FILENAME' => 'file.zip'
        ];

        /** @var Artifacts&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('postFile')
            ->with($this->equalTo('/suborganizations/suborganization/packages/artifacts/'), $rawFileContent, $headers)
            ->willReturn($expected);


        $this->assertSame($expected, $api->create($suborganizationName, $rawFileContent, $headers['Content-Type'], $headers['X-FILENAME']));
    }

    public function testAdd()
    {
        $suborganizationName = 'suborganization';
        $packageName = 'acme/artifact';
        $expected = [
            'id' => 1,
        ];
        $rawFileContent = 'foobar';
        $headers = [
            'Content-Type' => 'application/zip',
            'X-FILENAME' => 'file.zip'
        ];

        /** @var Artifacts&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('postFile')
            ->with($this->equalTo('/suborganizations/'.$suborganizationName.'/packages/'.$packageName.'/artifacts/'), $rawFileContent, $headers)
            ->willReturn($expected);


        $this->assertSame($expected, $api->add($suborganizationName, $packageName, $rawFileContent, $headers['Content-Type'], $headers['X-FILENAME']));
    }

    public function testShow()
    {
        $suborganizationName = 'suborganization';
        $expected = [
            'repoType' => 'artifact',
            'artifactPackageFileIds' =>[1, 2],
        ];

        /** @var Artifacts&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/suborganizations/suborganization/packages/artifacts/1/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->show($suborganizationName, '1'));
    }

    public function testShowPackageArtifacts()
    {
        $suborganizationName = 'suborganization';
        $expected = [
            'name' => 'acme-website/package',
            'repoType' => 'artifact',
            'artifactFiles' => 'artifact',
        ];

        /** @var Artifacts&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('getCollection')
            ->with($this->equalTo('/suborganizations/suborganization/packages/acme-website/package/artifacts/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->showPackageArtifacts($suborganizationName, 'acme-website/package'));
    }

    /**
     * @return string
     */
    protected function getApiClass()
    {
        return Artifacts::class;
    }
}
