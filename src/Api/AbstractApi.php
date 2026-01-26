<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use Composer\Pcre\Preg;
use PrivatePackagist\ApiClient\Client;
use PrivatePackagist\ApiClient\HttpClient\Message\ResponseMediator;

abstract class AbstractApi
{
    const DEFAULT_LIMIT = 500;

    /** @var Client */
    protected $client;
    /** @var ResponseMediator */
    private $responseMediator;

    public function __construct(Client $client, ?ResponseMediator $responseMediator = null)
    {
        $this->client = $client;
        $this->responseMediator = $responseMediator ?: new ResponseMediator();
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param array $headers
     * @return array|string
     */
    protected function get($path, array $parameters = [], array $headers = [])
    {
        if (count($parameters) > 0) {
            $path .= '?'.http_build_query($parameters);
        }
        $response = $this->client->getHttpClient()->get(
            $path,
            array_merge($headers, [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
        );

        return $this->responseMediator->getContent($response);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param array $headers
     * @return array
     */
    protected function getCollection($path, array $parameters = [], array $headers = [])
    {
        if (count($parameters) > 0) {
            $path .= '?'.http_build_query($parameters);
        }

        $content = [];
        $nextUrl = $path;

        do {
            $response = $this->client->getHttpClient()->get(
                $nextUrl,
                array_merge($headers, [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
            );

            $pageContent = $this->responseMediator->getContent($response);

            if ($response->getStatusCode() !== 200) {
                return $pageContent;
            }

            $content = array_merge($content, $pageContent);

            $nextUrl = null;
            if ($response->hasHeader('Link')) {
                $nextUrl = $this->parseLinkHeader($response->getHeaderLine('Link'), 'next');
            }
        } while ($nextUrl !== null);

        return $content;
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param array $headers
     * @return array|string
     */
    protected function post($path, array $parameters = [], array $headers = [])
    {
        $response = $this->client->getHttpClient()->post(
            $path,
            array_merge($headers, [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]),
            $this->createJsonBody($parameters)
        );

        return $this->responseMediator->getContent($response);
    }

    protected function postFile($path, $rawFileContent, array $headers = [])
    {
        $response = $this->client->getHttpClient()->post(
            $path,
            array_merge($headers, [
                'Accept' => 'application/json',
            ]),
            $rawFileContent
        );

        return $this->responseMediator->getContent($response);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param array $headers
     * @return array|string
     */
    protected function put($path, array $parameters = [], array $headers = [])
    {
        $response = $this->client->getHttpClient()->put(
            $path,
            array_merge($headers, [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]),
            $this->createJsonBody($parameters)
        );

        return $this->responseMediator->getContent($response);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param array $headers
     * @return array|string
     */
    protected function delete($path, array $parameters = [], array $headers = [])
    {
        $response = $this->client->getHttpClient()->delete(
            $path,
            array_merge($headers, [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]),
            $this->createJsonBody($parameters)
        );

        return $this->responseMediator->getContent($response);
    }
    /**
     * @param array $parameters
     * @return null|string
     */
    protected function createJsonBody(array $parameters)
    {
        return (count($parameters) === 0) ? null : json_encode($parameters);
    }

    /**
     * @param string $header
     * @param string $type
     * @return string|null
     */
    private function parseLinkHeader(string $header, string $type): ?string
    {
        foreach (explode(',', $header) as $relation) {
            if (Preg::isMatch('/<(.*)>; rel="(.*)"/i', \trim($relation, ','), $match)) {
                /** @var string[] $match */
                if (3 === count($match) && $match[2] === $type) {
                    return $match[1];
                }
            }
        }

        return null;
    }
}
