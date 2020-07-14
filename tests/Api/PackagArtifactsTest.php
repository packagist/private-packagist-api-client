<?php

/*
 * (c) Packagist Conductors UG (haftungsbeschränkt) <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

class PackagArtifactsTest extends ApiTestCase
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

        /** @var PackageArtifacts&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('postFile')
            ->with($this->equalTo('/packages/artifact/'), $rawFileContent, $headers)
            ->willReturn($expected);


        $this->assertSame($expected, $api->create($rawFileContent, $headers['Content-Type'], $headers['X-FILENAME']));
    }

    public function testShow()
    {
        $expected = [
            'repoType' => 'artifact',
            'artifactPackageFileIds' =>[1, 2],
        ];

        /** @var PackageArtifacts&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/packages/artifact/1/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->show('1'));
    }

    /**
     * @return string
     */
    protected function getApiClass()
    {
        return PackageArtifacts::class;
    }
}
