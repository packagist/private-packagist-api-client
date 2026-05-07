<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\HttpClient\Plugin;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

class RequestSignatureTest extends PluginTestCase
{
    /** @var RequestSignature */
    private $plugin;
    private $key;
    private $secret;
    private $timestamp;
    private $nonce;

    protected function setUp(): void
    {
        parent::setUp();

        $this->key = 'token';
        $this->secret = 'secret';
        $this->timestamp = 1518721253;
        $this->nonce = '78b9869e96cf58b5902154e0228f8576f042e5ac';
        $this->plugin = new RequestSignatureMock($this->key, $this->secret);
        $this->plugin->init($this->timestamp, $this->nonce);
    }

    public function testPrefixRequestPath()
    {
        $request = new Request('POST', '/packages/?foo=bar', [], json_encode(['foo' => 'bar']));
        $expected = new Request(
            'POST',
            '/packages/?foo=bar',
            [
                'Authorization' => ["PACKAGIST-HMAC-SHA256 Key={$this->key}, Timestamp={$this->timestamp}, Cnonce={$this->nonce}, Version=2, Signature=rzwvwGS17Qcmk8UqTefJCHCV188x1/e1iBWG2pB4z1M="],
            ],
            json_encode(['foo' => 'bar'])
        );

        $promise = $this->plugin->handleRequest($request, $this->next, $this->first);

        $this->assertEquals($expected->getHeaders(), $promise->wait(true)->getHeaders());
    }

    public function testSignatureCoversQueryString()
    {
        $requestA = new Request('GET', '/packages/?page=1&limit=10');
        $requestB = new Request('GET', '/packages/?page=2&limit=10');

        $signatureA = $this->extractSignature($this->plugin->handleRequest($requestA, $this->next, $this->first)->wait(true));
        $signatureB = $this->extractSignature($this->plugin->handleRequest($requestB, $this->next, $this->first)->wait(true));

        $this->assertNotSame($signatureA, $signatureB);
    }

    public function testSignatureIgnoresQueryParamOrder()
    {
        $requestA = new Request('GET', '/packages/?page=1&limit=10');
        $requestB = new Request('GET', '/packages/?limit=10&page=1');

        $signatureA = $this->extractSignature($this->plugin->handleRequest($requestA, $this->next, $this->first)->wait(true));
        $signatureB = $this->extractSignature($this->plugin->handleRequest($requestB, $this->next, $this->first)->wait(true));

        $this->assertSame($signatureA, $signatureB);
    }

    public function testQueryParamWithAuthFieldNameDoesNotShadowAuthIdentity()
    {
        $request = new Request('GET', '/packages/?key=evil&version=1');

        $header = $this->plugin->handleRequest($request, $this->next, $this->first)->wait(true)->getHeader('Authorization')[0];

        $this->assertStringContainsString("Key={$this->key}", $header);
        $this->assertStringContainsString('Version=2', $header);
    }

    public function testEmptyQueryStringMatchesNoQueryString()
    {
        $withoutQuery = new Request('GET', '/packages/');
        $withEmptyQuery = new Request('GET', '/packages/?');

        $signatureA = $this->extractSignature($this->plugin->handleRequest($withoutQuery, $this->next, $this->first)->wait(true));
        $signatureB = $this->extractSignature($this->plugin->handleRequest($withEmptyQuery, $this->next, $this->first)->wait(true));

        $this->assertSame($signatureA, $signatureB);
    }

    public function testEmptyValueQueryParamIsSigned()
    {
        $withoutParam = new Request('GET', '/packages/');
        $withEmptyValue = new Request('GET', '/packages/?foo=');
        $withValuelessParam = new Request('GET', '/packages/?foo');

        $baseline = $this->extractSignature($this->plugin->handleRequest($withoutParam, $this->next, $this->first)->wait(true));
        $emptyValue = $this->extractSignature($this->plugin->handleRequest($withEmptyValue, $this->next, $this->first)->wait(true));
        $valueless = $this->extractSignature($this->plugin->handleRequest($withValuelessParam, $this->next, $this->first)->wait(true));

        $this->assertNotSame($baseline, $emptyValue);
        $this->assertSame($emptyValue, $valueless);
    }

    public function testUrlEncodingIsCanonical()
    {
        $percent = new Request('GET', '/packages/?foo=hello%20world');
        $plus = new Request('GET', '/packages/?foo=hello+world');

        $signaturePercent = $this->extractSignature($this->plugin->handleRequest($percent, $this->next, $this->first)->wait(true));
        $signaturePlus = $this->extractSignature($this->plugin->handleRequest($plus, $this->next, $this->first)->wait(true));

        $this->assertSame($signaturePercent, $signaturePlus);
    }

    public function testV2GetDeterministicSignature()
    {
        $request = new Request('GET', 'https://localhost/api/packages/?limit=1&page=2');
        $expected = "PACKAGIST-HMAC-SHA256 Key={$this->key}, Timestamp={$this->timestamp}, Cnonce={$this->nonce}, Version=2, Signature=";

        $actual = $this->plugin->handleRequest($request, $this->next, $this->first)->wait(true)->getHeader('Authorization')[0];

        $this->assertStringStartsWith($expected, $actual);
        $this->assertStringEndsWith('Signature=RLb/mPCONIcfPp3+Ink+jtxNU6VKyeasf7Zdd7kNO+A=', $actual);
    }

    private function extractSignature(RequestInterface $request)
    {
        $header = $request->getHeader('Authorization')[0];
        preg_match('/Signature=([^,]+)/', $header, $matches);

        return $matches[1];
    }

    public function testPrefixRequestPathSmoke()
    {
        $request = new Request('POST', '/packages/?foo=bar', [], json_encode(['foo' => 'bar']));

        $promise = $this->plugin->handleRequest($request, $this->next, $this->first);

        $this->assertNotNull($promise->wait(true)->getHeader('Authorization')[0]);
    }

    /**
     * @dataProvider keySecretProvider
     */
    public function testMissingTokenOrSecret(string $key, string $secret): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new RequestSignature($key, $secret);
    }

    public function keySecretProvider(): array
    {
        return [
            ['', ''],
            ['key', ''],
            ['', 'secret'],
        ];
    }
}
