<?php

/*
 * (c) Packagist Conductors UG (haftungsbeschränkt) <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use Http\Client\HttpClient;
use PHPUnit\Framework\TestCase;
use PrivatePackagist\ApiClient\Client;
use PrivatePackagist\ApiClient\HttpClient\HttpPluginClientBuilder;

abstract class ApiTestCase extends TestCase
{
    /**
     * @return string
     */
    abstract protected function getApiClass();

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getApiMock()
    {
        /** @var HttpClient&\PHPUnit_Framework_MockObject_MockObject $httpClient */
        $httpClient = $this->getMockBuilder(HttpClient::class)
            ->setMethods(['sendRequest'])
            ->getMock();

        $httpClient
            ->expects($this->any())
            ->method('sendRequest');

        $client = new Client(new HttpPluginClientBuilder($httpClient));

        return $this->getMockBuilder($this->getApiClass())
            ->setMethods(['get', 'post', 'patch', 'delete', 'put', 'head'])
            ->setConstructorArgs([$client])
            ->getMock();
    }
}
