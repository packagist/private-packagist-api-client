<?php

/*
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\HttpClient\Plugin;

use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Http\Message\RequestMatcher as RequestMatcherInterface;
use Http\Mock\Client as MockClient;
use PHPUnit\Framework\TestCase;
use PrivatePackagist\ApiClient\HttpClient\HttpPluginClientBuilder;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpPluginClientBuilderTest extends TestCase
{
    /** @dataProvider provideRequestFactories */
    public function testRequestFactory(?object $factory, ?string $expectedException): void
    {
        if ($expectedException !== null) {
            $this->expectException($expectedException);
        }

        $mockHttp = new MockClient;
        $mockHttp->setDefaultException(new \Exception('Mock HTTP client did not match request.'));
        $mockHttp->on($this->matchRequestIncludingHeaders(), new Response(307, ['Location' => '/kittens.jpg']));

        $builder = new HttpPluginClientBuilder($mockHttp, $factory);
        // Make sure that the RequestFactory passed is acceptable for the client.
        $client = $builder->getHttpClient();
        $this->assertInstanceOf(HttpMethodsClientInterface::class, $client);

        // Ensure that the Request Factory correctly generates a request object (including headers
        // as RequestFactory and RequestFactoryInterface set headers differently).
        $response = $client->get('https://example.com/puppies.jpg', ['Accept' => 'image/vnd.cute+jpeg']);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(307, $response->getStatusCode());
        $locationHeaders = $response->getHeader('Location');
        $this->assertCount(1, $locationHeaders);
        $this->assertSame('/kittens.jpg', reset($locationHeaders));
    }

    /**
     * The concrete implementation of the RequestMatcher interface does not allow matching on
     * headers, which we need to test to ensure both legacy and PSR17 implementations work.
     */
    protected function matchRequestIncludingHeaders(): RequestMatcherInterface
    {
        return new class implements RequestMatcherInterface {
            public function matches(RequestInterface $request): bool
            {
                $acceptHeaders = $request->getHeader('Accept');
                return $request->getUri()->getPath() === '/puppies.jpg'
                    && count($acceptHeaders) === 1
                    && reset($acceptHeaders) === 'image/vnd.cute+jpeg';
            }
        };
    }

    /** @return iterable{object|null, class-string|null} */
    private static function provideRequestFactories(): iterable
    {
        // Fallback
        yield [null, null];
        // Legacy
        yield [new GuzzleMessageFactory, null];
        // PSR17
        yield [new HttpFactory, null];
        // Invalid
        yield [new \stdClass, \TypeError::class];
    }
}
