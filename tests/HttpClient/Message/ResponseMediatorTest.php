<?php

/*
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\HttpClient\Message;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ResponseMediatorTest extends TestCase
{
    /** @var ResponseMediator */
    private $responseMediator;

    protected function setUp(): void
    {
        $this->responseMediator = new ResponseMediator();
    }

    public function testGetContent()
    {
        $body = ['name' => 'composer/composer'];
        $response = new Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Psr7\stream_for(json_encode($body))
        );
        $this->assertSame($body, $this->responseMediator->getContent($response));
    }

    public function testGetContentNotJson()
    {
        $body = '';
        $response = new Response(
            200,
            [],
            \GuzzleHttp\Psr7\stream_for($body)
        );
        $this->assertSame($body, $this->responseMediator->getContent($response));
    }

    public function testGetContentInvalidJson()
    {
        $body = 'not-json';
        $response = new Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Psr7\stream_for($body)
        );
        $this->assertEquals($body, $this->responseMediator->getContent($response));
    }
}
