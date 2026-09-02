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
use PrivatePackagist\ApiClient\Exception\RuntimeException;
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

    /**
     * @dataProvider provideForeignPaginationLinks
     */
    public function testGetCollectionRefusesToFollowPaginationLinkToAnotherOrigin(string $nextUrl)
    {
        $response = new Response(
            200,
            [
                'Content-Type' => 'application/json',
                'Link' => sprintf('<%s>; rel="next"', $nextUrl),
            ],
            json_encode([['id' => 1, 'name' => 'acme/package1']])
        );

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn($response);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf(
            'Refusing to follow the pagination link "%s": it points outside the configured Private Packagist URL "https://packagist.com".',
            $nextUrl
        ));

        $this->api->testGetCollection('/packages/', ['limit' => AbstractApi::DEFAULT_LIMIT]);
    }

    /**
     * @return array<string, string[]>
     */
    public function provideForeignPaginationLinks()
    {
        return [
            'different host' => ['https://evil.example.com/api/packages?page=2'],
            'subdomain of the configured host' => ['https://evil.packagist.com/api/packages?page=2'],
            'configured host as a prefix' => ['https://packagist.com.evil.example/api/packages?page=2'],
            'userinfo pointing at another host' => ['https://packagist.com@evil.example.com/api/packages?page=2'],
            'scheme downgraded to http' => ['http://packagist.com/api/packages?page=2'],
            'same host on another port' => ['https://packagist.com:8443/api/packages?page=2'],
        ];
    }

    public function testGetCollectionFollowsPaginationLinkWithoutHost()
    {
        $page1 = [['id' => 1, 'name' => 'acme/package1']];
        $page2 = [['id' => 2, 'name' => 'acme/package2']];

        $response1 = new Response(
            200,
            [
                'Content-Type' => 'application/json',
                'Link' => '</api/packages?page=2&limit=500>; rel="next"',
            ],
            json_encode($page1)
        );

        $response2 = new Response(200, ['Content-Type' => 'application/json'], json_encode($page2));

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

    public function testGetCollectionFollowsPaginationLinkOnANonDefaultPortWhenConfiguredWithIt()
    {
        $client = new Client(new HttpPluginClientBuilder($this->httpClient), 'https://packagist.example:8443');
        $api = new TestableAbstractApi($client);

        $page1 = [['id' => 1, 'name' => 'acme/package1']];
        $page2 = [['id' => 2, 'name' => 'acme/package2']];

        $response1 = new Response(
            200,
            [
                'Content-Type' => 'application/json',
                'Link' => '<https://packagist.example:8443/api/packages?page=2&limit=500>; rel="next"',
            ],
            json_encode($page1)
        );

        $response2 = new Response(200, ['Content-Type' => 'application/json'], json_encode($page2));

        $matcher = $this->exactly(2);
        $this->httpClient
            ->expects($matcher)
            ->method('sendRequest')
            ->willReturnCallback(function (RequestInterface $request) use ($matcher, $response1, $response2) {
                return $matcher->getInvocationCount() === 1 ? $response1 : $response2;
            });

        $this->assertSame(array_merge($page1, $page2), $api->testGetCollection('/packages/', ['limit' => AbstractApi::DEFAULT_LIMIT]));
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
