<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use GuzzleHttp\Psr7\Response;
use Http\Client\HttpClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PrivatePackagist\ApiClient\Client;
use PrivatePackagist\ApiClient\HttpClient\HttpPluginClientBuilder;
use Psr\Http\Message\RequestInterface;

class AbstractApiTest extends TestCase
{
    /**
     * @var TestableAbstractApi
     */
    private $api;

    /**
     * @var HttpClient&MockObject
     */
    private $httpClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->httpClient = $this->getMockBuilder(HttpClient::class)
            ->setMethods(['sendRequest'])
            ->getMock();

        $client = new Client(new HttpPluginClientBuilder($this->httpClient));
        $this->api = new TestableAbstractApi($client);
    }

    public function testGetCollectionWithPagination()
    {
        $page1 = [
            ['id' => 1, 'name' => 'acme/package1'],
        ];

        $page2 = [
            ['id' => 2, 'name' => 'acme/package2'],
        ];

        $response1 = new Response(
            200,
            [
                'Content-Type' => 'application/json',
                'Link' => '<https://packagist.com/api/packages?page=1>; rel="first", <https://packagist.com/api/packages?page=2&limit=500>; rel="next", <https://packagist.com/api/packages?page=2&limit=500>; rel="last"',
            ],
            json_encode($page1)
        );

        $response2 = new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($page2)
        );

        $matcher = $this->exactly(2);
        $this->httpClient
            ->expects($matcher)
            ->method('sendRequest')
            ->willReturnCallback(function (RequestInterface $request) use ($matcher, $response1, $response2) {
                $uri = (string) $request->getUri();

                switch ($matcher->getInvocationCount()) {
                    case 1:
                        $this->assertSame('https://packagist.com/api/packages/?limit=500', $uri);
                        return $response1;
                    case 2:
                        $this->assertSame('https://packagist.com/api/packages?page=2&limit=500', $uri);
                        return $response2;
                }

                $this->fail('Unexpected request to: ' . $uri);
            });

        $result = $this->api->testGetCollection('/packages/', ['limit' => AbstractApi::DEFAULT_LIMIT]);

        $this->assertSame(array_merge($page1, $page2), $result);
    }
}

/**
 * Testable concrete implementation of AbstractApi for testing purposes
 */
class TestableAbstractApi extends AbstractApi
{
    public function testGetCollection($path, array $parameters = [], array $headers = [])
    {
        return $this->getCollection($path, $parameters, $headers);
    }
}
