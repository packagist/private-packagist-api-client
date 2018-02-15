<?php

namespace PrivatePackagist\ApiClient\HttpClient\Plugin;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

class RequestSignatureTest extends TestCase
{
    /** @var RequestSignature */
    private $plugin;
    private $token;
    private $secret;
    private $timestamp;
    private $nonce;

    protected function setUp()
    {
        $this->token = 'token';
        $this->secret = 'secret';
        $this->timestamp = 1518721253;
        $this->nonce = '78b9869e96cf58b5902154e0228f8576f042e5ac';
        $this->plugin = new RequestSignatureMock($this->token, $this->secret);
        $this->plugin->init($this->timestamp, $this->nonce);
    }

    public function testPrefixRequestPath()
    {
        $request = new Request('POST', '/packages/?foo=bar', [], json_encode(['foo' => 'bar']));
        $expected = new Request(
            'POST',
            '/packages/?foo=bar',
            [
                'PRIVATE-PACKAGIST-API-TOKEN' => $this->token,
                'PRIVATE-PACKAGIST-API-TIMESTAMP' => $this->timestamp,
                'PRIVATE-PACKAGIST-API-NONCE' => $this->nonce,
                'PRIVATE-PACKAGIST-API-SIGNATURE' => 'a6wxBLYrmz4Mwmv/TKBZR5WHFcSCRbsny2frobJMt24=',
            ],
            json_encode(['foo' => 'bar'])
        );

        $this->plugin->handleRequest($request, function (Request $actual) use ($expected) {
            $this->assertEquals($expected->getHeaders(), $actual->getHeaders());
        }, function () {
        });
    }
}
