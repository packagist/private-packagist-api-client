<?php

namespace PrivatePackagist\ApiClient\Api;

class OrganizationTest extends ApiTestCase
{
    public function testSync()
    {
        $expected = [];

        /** @var Organization&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/organization/sync'))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->sync());
    }

    /**
     * @return string
     */
    protected function getApiClass()
    {
        return Organization::class;
    }
}
