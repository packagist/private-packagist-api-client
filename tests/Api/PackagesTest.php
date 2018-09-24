<?php

namespace PrivatePackagist\ApiClient\Api;

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

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/packages/'))
            ->will($this->returnValue($expected));

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

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/packages/'), $this->equalTo($filters))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->all($filters));
    }

    /**
     * @expectedException \PrivatePackagist\ApiClient\Exception\InvalidArgumentException
     */
    public function testAllWithInvalidFilters()
    {
        $filters = [
            'origin' => 'invalid'
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
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

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/packages/acme-website/package/'))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->show('acme-website/package'));
    }

    public function testCreateVcsPackage()
    {
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/packages/'), $this->equalTo(['repoType' => 'vcs', 'repoUrl' => 'localhost', 'credentials' => null]))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->createVcsPackage('localhost'));
    }

    public function testCreateCustomPackage()
    {
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/packages/'), $this->equalTo(['repoType' => 'package', 'repoConfig' => '{}', 'credentials' => null]))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->createCustomPackage('{}'));
    }

    public function testUpdateVcsPackage()
    {
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/packages/acme-website/package/'), $this->equalTo(['repoType' => 'vcs', 'repoUrl' => 'localhost', 'credentials' => null]))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->updateVcsPackage('acme-website/package', 'localhost'));
    }

    public function testUpdateCustomPackage()
    {
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/packages/acme-website/package/'), $this->equalTo(['repoType' => 'package', 'repoConfig' => '{}', 'credentials' => null]))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->updateCustomPackage('acme-website/package', '{}'));
    }

    public function testRemove()
    {
        $expected = [];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/packages/acme-website/package/'))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->remove('acme-website/package'));
    }

    protected function getApiClass()
    {
        return Packages::class;
    }
}
