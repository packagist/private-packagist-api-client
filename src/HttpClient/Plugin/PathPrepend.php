<?php

namespace PrivatePackagist\ApiClient\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Psr\Http\Message\RequestInterface;

class PathPrepend implements Plugin
{
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
    public function handleRequest(RequestInterface $request, callable $next, callable $first)
    {
        $uri = $request->getUri();

        if (strpos($uri->getPath(), $this->path) !== 0) {
            $request = $request->withUri($uri->withPath($this->path . $uri->getPath()));
        }

        return $next($request);
    }
}
