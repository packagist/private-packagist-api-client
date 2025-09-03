<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\HttpClient\Plugin;

use GuzzleHttp\Psr7\Request;

class PathPrependTest extends PluginTestCase
{
    /** @var PathPrepend */
    private $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PathPrepend('/api');
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
