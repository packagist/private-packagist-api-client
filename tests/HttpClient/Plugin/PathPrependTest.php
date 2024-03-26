<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\HttpClient\Plugin;

use GuzzleHttp\Psr7\Request;
use Http\Promise\FulfilledPromise;
use PHPUnit\Framework\TestCase;

class PathPrependTest extends TestCase
{
    /** @var PathPrepend */
    private $plugin;
    private $next;
    private $first;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PathPrepend('/api');
        $this->next = function (Request $request) {
            return new FulfilledPromise($request);
        };
        $this->first = function () {
            throw new \RuntimeException('Did not expect plugin to call first');
        };
    }

    /**
     * @dataProvider pathProvider
     */
    public function testPrefixApiRequestPath($path, $expectedPath)
    {
        $request = new Request('GET', $path);
        $expected = new Request('GET', $expectedPath);

        $promise = $this->plugin->handleRequest($request, $this->next, $this->first);

        $this->assertEquals($expected, $promise->wait(true));
    }

    public function pathProvider()
    {
        return [
            ['/api/packages/', '/api/packages/'],
            ['/packages/', '/api/packages/'],
        ];
    }
}
