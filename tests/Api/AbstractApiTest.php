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
use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;
use PrivatePackagist\ApiClient\Exception\RuntimeException;
use PrivatePackagist\ApiClient\Exception\HttpTransportException;
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

    public function testRequestPayloadIsSentUnchanged()
    {
        $sentRequest = null;
        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturnCallback(function (RequestInterface $request) use (&$sentRequest) {
                $sentRequest = $request;

                return new Response(200, ['Content-Type' => 'application/json'], json_encode(['id' => 1]));
            });

        $credentials = new Credentials(new Client(new HttpPluginClientBuilder($this->httpClient)));
        $credentials->create('description', 'http-basic', 'github.com', 'username', 'a-secret');

        $this->assertNotNull($sentRequest);
        $this->assertSame(
            '{"description":"description","type":"http-basic","domain":"github.com","username":"username","credential":"a-secret"}',
            (string) $sentRequest->getBody()
        );
    }

    public function testRequestPayloadIsNotCapturedAsAStackTraceArgument()
    {
        if (!class_exists(\SensitiveParameter::class)) {
            $this->markTestSkipped('#[\SensitiveParameter] only redacts arguments as of PHP 8.2.');
        }

        $secret = 'the-credential-that-must-not-leak';

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn(new Response(500, ['Content-Type' => 'application/json'], json_encode(['message' => 'boom'])));

        $credentials = new Credentials(new Client(new HttpPluginClientBuilder($this->httpClient)));

        try {
            $credentials->create('description', 'http-basic', 'github.com', 'username', $secret);
            $this->fail('Expected the error response to raise an exception.');
        } catch (HttpTransportException $e) {
            $this->assertPayloadNotInStackTrace($e, $secret);
        }
    }

    /**
     * @param string $secret
     */
    private function assertPayloadNotInStackTrace(\Throwable $exception, $secret)
    {
        foreach ($exception->getTrace() as $frame) {
            $this->assertStringNotContainsString(
                $secret,
                print_r(isset($frame['args']) ? $frame['args'] : [], true),
                sprintf(
                    'Payload exposed in stack trace argument of %s%s%s().',
                    isset($frame['class']) ? $frame['class'] : '',
                    isset($frame['type']) ? $frame['type'] : '',
                    $frame['function']
                )
            );
        }
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

    /** @dataProvider providePathArguments */
    public function testBuildPathEncodesArguments($argument, $expectedPath)
    {
        $this->assertSame($expectedPath, $this->api->testBuildPath('/packages/%s/', $argument));
    }

    public function providePathArguments()
    {
        return [
            'plain name' => ['acme-customer', '/packages/acme-customer/'],
            'a package name keeps its separator, encoded' => ['acme/package', '/packages/acme%2Fpackage/'],
            'dots in a name are legal' => ['symfony/polyfill-php7.2', '/packages/symfony%2Fpolyfill-php7.2/'],
            'integer id' => [42, '/packages/42/'],
            'fragment cannot end the path' => ['#', '/packages/%23/'],
            'query cannot be smuggled' => ['?limit=1', '/packages/%3Flimit%3D1/'],
            'space' => ['a b', '/packages/a%20b/'],
            'percent is escaped so encoding cannot be forged' => ['%2F', '/packages/%252F/'],
            'traversal stays inside one segment' => ['../../foo', '/packages/..%2F..%2Ffoo/'],
            'empty inner segment' => ['acme//package', '/packages/acme%2F%2Fpackage/'],
            'leading separator' => ['/acme', '/packages/%2Facme/'],
        ];
    }

    /** @dataProvider provideRejectedPathArguments */
    public function testBuildPathRejectsArgument($argument, $expectedMessage)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);

        $this->api->testBuildPath('/packages/%s/', $argument);
    }

    public function provideRejectedPathArguments()
    {
        return [
            'empty' => ['', "Path arguments must not be empty, '.', or '..'"],
            'null' => [null, 'Path arguments must be a string or an integer, NULL given.'],
            'array' => [[], 'Path arguments must be a string or an integer, array given.'],
            // rawurlencode() leaves these two intact, so they are the only values that still reach
            // the wire as a dot segment once the separators around them are encoded.
            'current directory' => ['.', "Path arguments must not be empty, '.', or '..'"],
            'parent directory' => ['..', "Path arguments must not be empty, '.', or '..'"],
        ];
    }

    /**
     * The signature is computed over the path after the URI has been parsed, so a truncated path is
     * signed as if it had been asked for. This asserts on the wire, not on the string handed to
     * delete(), because that is where the truncation used to happen.
     */
    public function testInjectedPathArgumentCannotChangeTheEndpointOnTheWire()
    {
        $client = new Client(new HttpPluginClientBuilder($this->httpClient), 'https://packagist.example');

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturnCallback(function (RequestInterface $request) {
                $this->assertSame('/api/customers/acme-customer/packages/%23/', $request->getUri()->getPath());
                $this->assertSame('', $request->getUri()->getQuery());
                $this->assertSame('', $request->getUri()->getFragment());

                return new Response(200, ['Content-Type' => 'application/json'], '[]');
            });

        $client->customers()->removePackage('acme-customer', '#');
    }

    /**
     * The plugin chain rewrites the URI on the way out, so this pins down that an encoded package
     * name survives it neither decoded nor encoded a second time.
     */
    public function testEncodedPackageNameReachesTheWireIntact()
    {
        $client = new Client(new HttpPluginClientBuilder($this->httpClient), 'https://packagist.example');

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturnCallback(function (RequestInterface $request) {
                $this->assertSame('/api/customers/acme-customer/packages/acme%2Fpackage/', $request->getUri()->getPath());

                return new Response(200, ['Content-Type' => 'application/json'], '[]');
            });

        $client->customers()->removePackage('acme-customer', 'acme/package');
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

    public function testBuildPath($template, ...$arguments)
    {
        return $this->buildPath($template, ...$arguments);
    }
}
