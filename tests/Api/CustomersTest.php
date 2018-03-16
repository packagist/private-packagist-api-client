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
                'name' => 'composer/composer',
                'origin' => 'private',
                'versionConstraint' => null,
                'expirationDate' => null,
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

    public function testAddOrUpdatePackages()
    {
        $expected = [
            [
                'name' => 'composer/composer',
                'origin' => 'private',
                'versionConstraint' => null,
                'expirationDate' => null,
            ],
        ];

        $packages = [['name' => 'composer/composer']];

        /** @var Customers&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/customers/1/packages/'), $this->equalTo($packages))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->addOrUpdatePackages(1, $packages));
    }

    /**
     * @expectedException \PrivatePackagist\ApiClient\Exception\InvalidArgumentException
     * @expectedExceptionMessage Parameter "name" is requried.
     */
    public function testAddOrUpdatePackagesMissingName()
    {
        /** @var Customers&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();

        $api->addOrUpdatePackages(1, [[]]);
    }

    public function testRemovePackage()
    {
        $expected = '';
        $packageName = 'composer/composer';

        /** @var Customers&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo(sprintf('/customers/1/packages/%s/', $packageName)))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->removePackage(1, $packageName));
    }

    public function testRegenerateToken()
    {
        $expected = [
            'url' => 'https://repo.packagist.com/acme-website/',
            'user' => 'token',
            'token' => 'regenerated-token',
            'lastUsed' => null,
        ];

        $confirmation = [
            'IConfirmOldTokenWillStopWorkingImmediately' => true,
        ];

        /** @var Customers&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/customers/1/token/regenerate'), $this->equalTo($confirmation))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->regenerateToken(1, $confirmation));
    }

    /**
     * @expectedException \PrivatePackagist\ApiClient\Exception\InvalidArgumentException
     */
    public function testRegenerateTokenMissingConfirmation()
    {
        /** @var Customers&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->never())
            ->method('post');

        $api->regenerateToken(1, []);
    }

    protected function getApiClass()
    {
        return Customers::class;
    }
}
