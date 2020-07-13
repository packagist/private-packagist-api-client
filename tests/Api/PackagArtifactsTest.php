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
            'Content-Length' => '6',
            'X-FILENAME' => 'file.zip'
        ];

        /** @var PackageArtifact&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('postFile')
            ->with($this->equalTo('/packages/artifact/'), $rawFileContent, $headers)
            ->willReturn($expected);


        $this->assertSame($expected, $api->create($rawFileContent, $headers['Content-Type'], $headers['X-FILENAME']));
    }

    /**
     * @return string
     */
    protected function getApiClass()
    {
        return PackageArtifact::class;
    }
}
