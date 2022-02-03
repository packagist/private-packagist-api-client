<?php

/*
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\HttpClient\Plugin;

use GuzzleHttp\Psr7\Request;
use Http\Promise\FulfilledPromise;
use PHPUnit\Framework\TestCase;

class RequestSignatureTest extends TestCase
{
    /** @var RequestSignature */
    private $plugin;
    private $next;
    private $first;
    private $token;
    private $secret;
    private $timestamp;
    private $nonce;

    protected function setUp(): void
    {
        $this->token = 'token';
        $this->secret = 'secret';
        $this->timestamp = 1518721253;
        $this->nonce = '78b9869e96cf58b5902154e0228f8576f042e5ac';
        $this->plugin = new RequestSignatureMock($this->token, $this->secret);
        $this->plugin->init($this->timestamp, $this->nonce);
        $this->next = function (Request $request) {
            return new FulfilledPromise($request);
        };
        $this->first = function () {
            throw new \RuntimeException('Did not expect plugin to call first');
        };
    }

    public function testPrefixRequestPath()
    {
        $request = new Request('POST', '/packages/?foo=bar', [], json_encode(['foo' => 'bar']));
        $expected = new Request(
            'POST',
            '/packages/?foo=bar',
            [
                'Authorization' => ["PACKAGIST-HMAC-SHA256 Key={$this->token}, Timestamp={$this->timestamp}, Cnonce={$this->nonce}, Signature=a6wxBLYrmz4Mwmv/TKBZR5WHFcSCRbsny2frobJMt24="],
            ],
            json_encode(['foo' => 'bar'])
        );

        $promise = $this->plugin->handleRequest($request, $this->next, $this->first);

        $this->assertEquals($expected->getHeaders(), $promise->wait(true)->getHeaders());
    }

    public function testPrefixRequestPathSmoke()
    {
        $request = new Request('POST', '/packages/?foo=bar', [], json_encode(['foo' => 'bar']));

        $promise = $this->plugin->handleRequest($request, $this->next, $this->first);

        $this->assertNotNull($promise->wait(true)->getHeader('Authorization')[0]);
    }

    /**
     * @dataProvider tokenSecretProvider
     */
    public function testMissingTokenOrSecret(string $token, string $secret): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new RequestSignature($token, $secret);
    }

    public function tokenSecretProvider(): array
    {
        return [
            ['', ''],
            ['token', ''],
            ['', 'secret'],
        ];
    }
}
