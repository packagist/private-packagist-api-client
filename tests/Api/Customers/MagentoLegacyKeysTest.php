<?php

namespace PrivatePackagist\ApiClient\Api\Customers;

use PrivatePackagist\ApiClient\Api\ApiTestCase;

class MagentoLegacyKeysTest extends ApiTestCase
{
    public function testAll()
    {
        $expected = [
            [
                'publicKey' => 'public-jdgkfdgk233443554mn45',
                'privateKey' => 'private-fjdgkfdgk233443554mn45',
            ],
        ];

        /** @var MagentoLegacyKeys&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/api/customers/13/magento-legacy-keys/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all(13));
    }

    public function testCreate()
    {
        $expected = [
            'publicKey' => 'public-jdgkfdgk233443554mn45',
            'privateKey' => 'private-fjdgkfdgk233443554mn45',
        ];

        /** @var MagentoLegacyKeys&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/api/customers/13/magento-legacy-keys/'), $this->equalTo($expected))
            ->willReturn($expected);

        $this->assertSame($expected, $api->create(13, 'public-jdgkfdgk233443554mn45', 'private-fjdgkfdgk233443554mn45'));
    }

    public function testRemove()
    {
        $expected = [];

        /** @var MagentoLegacyKeys&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/api/customers/13/magento-legacy-keys/public-jdgkfdgk233443554mn45/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->remove(13, 'public-jdgkfdgk233443554mn45'));
    }

    protected function getApiClass()
    {
        return MagentoLegacyKeys::class;
    }
}
