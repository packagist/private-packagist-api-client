<?php

/*
 * (c) Packagist Conductors UG (haftungsbeschränkt) <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Psr\Http\Message\RequestInterface;

class PathPrepend implements Plugin
{
    use Plugin\VersionBridgePlugin;

    /** @var string */
    private $path;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    protected function doHandleRequest(RequestInterface $request, callable $next, callable $first)
    {
        $uri = $request->getUri();

        if (strpos($uri->getPath(), $this->path) !== 0) {
            $request = $request->withUri($uri->withPath($this->path . $uri->getPath()));
        }

        return $next($request);
    }
}
