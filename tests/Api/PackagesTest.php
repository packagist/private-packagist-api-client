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

    protected function getApiClass()
    {
        return Packages::class;
    }
}
