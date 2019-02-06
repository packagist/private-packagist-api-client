<?php

/*
 * (c) Packagist Conductors UG (haftungsbeschränkt) <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

class JobsTest extends ApiTestCase
{
    public function testShow()
    {
        $expected = [];
        $jobId = '46bf13150a86fece079ca979cb8ef57c78773faa';

        /** @var Jobs&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/jobs/' . $jobId))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->show($jobId));
    }

    protected function getApiClass()
    {
        return Jobs::class;
    }
}
