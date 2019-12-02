<?php

/*
 * (c) Packagist Conductors UG (haftungsbeschränkt) <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use PrivatePackagist\ApiClient\Exception\ErrorException;
use PrivatePackagist\ApiClient\Exception\HttpTransportException;
use PrivatePackagist\ApiClient\Exception\ResourceNotFoundException;
use PrivatePackagist\ApiClient\Exception\RuntimeException;
use PrivatePackagist\ApiClient\HttpClient\Message\ResponseMediator;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ExceptionThrower implements Plugin
{
    /** @var ResponseMediator */
    private $responseMediator;

    public function __construct(ResponseMediator $responseMediator = null)
    {
        $this->responseMediator = $responseMediator ? $responseMediator : new ResponseMediator();
    }

    public function handleRequest(RequestInterface $request, callable $next, callable $first)
    {
        return $next($request)->then(function (ResponseInterface $response) use ($request) {
            if ($response->getStatusCode() < 400 || $response->getStatusCode() > 600) {
                return $response;
            }

            $content = $this->responseMediator->getContent($response);
            if (is_array($content) && isset($content['message'])) {
                if ($response->getStatusCode() === 400) {
                    throw new ErrorException($content['message'], $response->getStatusCode());
                }

                if ($response->getStatusCode() === 404) {
                    throw new ResourceNotFoundException($content['message'], $response->getStatusCode(), $request->getUri());
                }
            }

            throw new HttpTransportException(isset($content['message']) ? $content['message'] : $content, $response->getStatusCode(), $request->getUri());
        });
    }
}
