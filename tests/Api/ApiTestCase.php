<?php

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
        $httpClient = $this->getMockBuilder(HttpClient::class)
            ->setMethods(['sendRequest'])
            ->getMock();

        $httpClient
            ->expects($this->any())
            ->method('sendRequest');

        $client = new Client(new HttpPluginClientBuilder($httpClient));

        return $this->getMockBuilder($this->getApiClass())
            ->setMethods(['get', 'post', 'postRaw', 'patch', 'delete', 'put', 'head'])
            ->setConstructorArgs([$client])
            ->getMock();
    }
}
