<?php

/*
 * (c) Packagist Conductors UG (haftungsbeschränkt) <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api\Subrepositories;

use PrivatePackagist\ApiClient\Api\ApiTestCase;

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

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/subrepositories/subrepository/packages/'))
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

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/subrepositories/subrepository/packages/'), $this->equalTo($filters))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all($subrepositoryName, $filters));
    }

    /**
     * @expectedException \PrivatePackagist\ApiClient\Exception\InvalidArgumentException
     */
    public function testAllWithInvalidFilters()
    {
        $subrepositoryName = 'subrepository';
        $filters = [
            'origin' => 'invalid'
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
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

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/subrepositories/subrepository/packages/acme-website/package'))
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

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
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

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
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

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
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

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
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

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/subrepositories/subrepository/packages/acme-website/package/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->remove($subrepositoryName, 'acme-website/package'));
    }

    protected function getApiClass()
    {
        return Packages::class;
    }
}
