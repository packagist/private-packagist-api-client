<?php declare(strict_types=1);

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\HttpClient\Plugin;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Mock\Client;
use Http\Promise\FulfilledPromise;
use PHPUnit\Framework\MockObject\MockObject;
use PrivatePackagist\ApiClient\HttpClient\HttpPluginClientBuilder;
use PrivatePackagist\OIDC\Identities\Token;
use PrivatePackagist\OIDC\Identities\TokenGeneratorInterface;
use Psr\Http\Message\RequestInterface;

class TrustedPublishingTokenExchangeTest extends PluginTestCase
{
    /** @var TrustedPublishingTokenExchange */
    private $plugin;
    /** @var Client */
    private $httpClient;
    /** @var TokenGeneratorInterface&MockObject  */
    private $tokenGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new TrustedPublishingTokenExchange(
            'organization',
            'acme/package',
            new HttpPluginClientBuilder($this->httpClient = new Client()),
            $this->tokenGenerator = $this->createMock(TokenGeneratorInterface::class)
        );
    }

    public function testTokenExchange(): void
    {
        $request = new Request('GET', '/api/packages/acme/package');

        $this->tokenGenerator
            ->expects($this->once())
            ->method('generate')
            ->with($this->identicalTo('test'))
            ->willReturn(Token::fromTokenString('test.test.test'));

        $this->httpClient->addResponse(new Response(200, [], json_encode(['audience' => 'test'])));
        $this->httpClient->addResponse(new Response(200, [], json_encode(['key' => 'key', 'secret' => 'secret'])));

        $this->plugin->handleRequest($request, function (RequestInterface $request) use (&$requestAfterPlugin) {
            $requestAfterPlugin = $request;

            return new FulfilledPromise($request);
        }, $this->first);

        $requests = $this->httpClient->getRequests();
        $this->assertCount(2, $requests);
        $this->assertSame('/oidc/audience', (string) $requests[0]->getUri());
        $this->assertSame('/oidc/token-exchange/organization/acme/package', (string) $requests[1]->getUri());

        $this->assertStringContainsString('PACKAGIST-HMAC-SHA256 Key=key', $requestAfterPlugin->getHeader('Authorization')[0]);
    }

    public function testNoTokenGenerated(): void
    {
        $request = new Request('GET', '/api/packages/acme/package');

        $this->tokenGenerator
            ->expects($this->once())
            ->method('generate')
            ->with($this->identicalTo('test'))
            ->willReturn(null);

        $this->httpClient->addResponse(new Response(200, [], json_encode(['audience' => 'test'])));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to generate OIDC token');

        $this->plugin->handleRequest($request, $this->next, $this->first);
    }
}
