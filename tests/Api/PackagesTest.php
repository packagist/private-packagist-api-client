<?php

namespace PrivatePackagist\ApiClient\Api;

class PackagesTest extends ApiTestCase
{
    public function testAll()
    {
        $expected = [
            [
                'id' => 1,
                'name' => 'composer/composer',
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

    protected function getApiClass()
    {
        return Packages::class;
    }
}
