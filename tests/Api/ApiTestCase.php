<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use Http\Client\HttpClient;
use PHPUnit\Framework\MockObject\MockObject;
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
     * @return MockObject
     */
    protected function getApiMock()
    {
        /** @var HttpClient&MockObject $httpClient */
        $httpClient = $this->getMockBuilder(HttpClient::class)
            ->setMethods(['sendRequest'])
            ->getMock();

        $httpClient
            ->expects($this->any())
            ->method('sendRequest');

        $client = new Client(new HttpPluginClientBuilder($httpClient));

        return $this->getMockBuilder($this->getApiClass())
            ->setMethods(['get', 'getCollection', 'post', 'postFile', 'patch', 'delete', 'put', 'head'])
            ->setConstructorArgs([$client])
            ->getMock();
    }
}
