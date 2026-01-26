<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api\Packages;

use PHPUnit\Framework\MockObject\MockObject;
use PrivatePackagist\ApiClient\Api\AbstractApi;
use PrivatePackagist\ApiClient\Api\ApiTestCase;

class ArtifactsTest extends ApiTestCase
{
    public function testCreate()
    {
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
            ->with($this->equalTo('/packages/artifacts/'), $rawFileContent, $headers)
            ->willReturn($expected);


        $this->assertSame($expected, $api->create($rawFileContent, $headers['Content-Type'], $headers['X-FILENAME']));
    }

    public function testAdd()
    {
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
            ->with($this->equalTo('/packages/'.$packageName.'/artifacts/'), $rawFileContent, $headers)
            ->willReturn($expected);


        $this->assertSame($expected, $api->add($packageName, $rawFileContent, $headers['Content-Type'], $headers['X-FILENAME']));
    }

    public function testShow()
    {
        $expected = [
            'repoType' => 'artifact',
            'artifactPackageFileIds' =>[1, 2],
        ];

        /** @var Artifacts&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/packages/artifacts/1/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->show('1'));
    }

    public function testShowPackageArtifacts()
    {
        $expected = [
            'name' => 'acme-website/package',
            'repoType' => 'artifact',
            'artifactFiles' => 'artifact',
        ];

        /** @var Artifacts&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/packages/acme-website/package/artifacts/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->showPackageArtifacts('acme-website/package'));
    }

    /**
     * @return string
     */
    protected function getApiClass()
    {
        return Artifacts::class;
    }
}
