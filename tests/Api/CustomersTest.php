<?php

namespace PrivatePackagist\ApiClient\Api;

class CustomersTest extends ApiTestCase
{
    public function testAll()
    {
        $expected = [
            [
                'id' => 1,
                'type' => 'composer-repo',
                'name' => 'Customer',
            ],
        ];

        /** @var Customers&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/customers/'))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->all());
    }

    public function testCreate()
    {
        $expected = [
            [
                'id' => 1,
                'type' => 'composer-repo',
                'name' => $name = 'Customer',
            ],
        ];

        /** @var Customers&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/customers/'), $this->equalTo(['name' => $name]))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->create($name));
    }

    public function testRemove()
    {
        $expected = '';

        /** @var Customers&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/customers/1/'))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->remove(1));
    }

    public function testListPackages()
    {
        $expected = [
            [
                'id' => 1,
                'name' => 'composer/composer',
            ],
        ];

        /** @var Customers&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/customers/1/packages/'))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->listPackages(1));
    }

    public function testAddPackages()
    {
        $expected = [
            [
                'id' => 2,
                'name' => 'composer/composer',
            ],
        ];

        $packages = [['id' => 1]];

        /** @var Customers&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/customers/1/packages/'), $this->equalTo($packages))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->addPackages(1, $packages));
    }

    public function testRemovePackage()
    {
        $expected = '';

        /** @var Customers&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/customers/1/packages/2/'))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->removePackage(1, 2));
    }

    protected function getApiClass()
    {
        return Customers::class;
    }
}
