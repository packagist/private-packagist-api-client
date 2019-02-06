<?php

/*
 * (c) Packagist Conductors UG (haftungsbeschränkt) <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\HttpClient\Plugin;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

class PathPrependTest extends TestCase
{
    public function testPrefixRequestPath()
    {
        $request = new Request('GET', '/packages/');
        $expected = new Request('GET', '/api/packages/');
        $plugin = new PathPrepend('/api');
        $callback = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['next'])
            ->getMock()
        ;
        $callback->expects($this->once())
            ->method('next')
            ->with($expected)
        ;

        $plugin->handleRequest($request, [$callback, 'next'], function () {
        });
    }

    public function testDontPrefixApiRequestPath()
    {
        $request = new Request('GET', '/api/packages/');
        $expected = new Request('GET', '/api/packages/');
        $plugin = new PathPrepend('/api');
        $callback = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['next'])
            ->getMock()
        ;
        $callback->expects($this->once())
            ->method('next')
            ->with($expected)
        ;

        $plugin->handleRequest($request, [$callback, 'next'], function () {
        });
    }
}
