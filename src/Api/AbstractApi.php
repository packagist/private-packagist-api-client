<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use Http\Discovery\Psr17FactoryDiscovery;
use PrivatePackagist\ApiClient\Client;
use PrivatePackagist\ApiClient\Exception\RuntimeException;
use PrivatePackagist\ApiClient\HttpClient\Message\ResponseMediator;
use Psr\Http\Message\UriInterface;

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
        $parameters = array_merge(['limit' => self::DEFAULT_LIMIT], $parameters);
        $path .= '?'.http_build_query($parameters);

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
                if ($nextUrl !== null) {
                    $this->assertSameOriginAsPrivatePackagist($nextUrl);
                }
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
    protected function post(
        $path,
        #[\SensitiveParameter]
        array $parameters = [],
        array $headers = []
    ) {
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
    protected function put(
        $path,
        #[\SensitiveParameter]
        array $parameters = [],
        array $headers = []
    ) {
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
    protected function delete(
        $path,
        #[\SensitiveParameter]
        array $parameters = [],
        array $headers = []
    ) {
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
     * The "next" link is chosen by the server. Following it unchecked would let any response steer a
     * signed, authenticated request at an arbitrary host and feed its reply back to the caller as if
     * it came from Private Packagist, so only same-origin links are followed. Links without a host
     * are fine: AddHostPlugin resolves those against the configured URL.
     *
     * @param string $nextUrl
     * @return void
     */
    private function assertSameOriginAsPrivatePackagist($nextUrl)
    {
        try {
            $uri = Psr17FactoryDiscovery::findUriFactory()->createUri($nextUrl);
        } catch (\InvalidArgumentException $e) {
            throw new RuntimeException(sprintf('Pagination link "%s" is not a valid URL.', $nextUrl), 0, $e);
        }

        if ($uri->getHost() === '') {
            return;
        }

        $privatePackagistUrl = $this->client->getPrivatePackagistUrl();
        if (strcasecmp($uri->getScheme(), $privatePackagistUrl->getScheme()) === 0
            && strcasecmp($uri->getHost(), $privatePackagistUrl->getHost()) === 0
            && $this->effectivePort($uri) === $this->effectivePort($privatePackagistUrl)
        ) {
            return;
        }

        throw new RuntimeException(sprintf(
            'Refusing to follow the pagination link "%s": it points outside the configured Private Packagist URL "%s".',
            $nextUrl,
            (string) $privatePackagistUrl
        ));
    }

    /**
     * @return int|null
     */
    private function effectivePort(UriInterface $uri)
    {
        $port = $uri->getPort();
        if ($port !== null) {
            return $port;
        }

        $defaultPorts = ['http' => 80, 'https' => 443];
        $scheme = strtolower($uri->getScheme());

        return isset($defaultPorts[$scheme]) ? $defaultPorts[$scheme] : null;
    }

    /**
     * @param string $header
     * @param string $type
     * @return string|null
     */
    private function parseLinkHeader(string $header, string $type): ?string
    {
        foreach (explode(',', $header) as $relation) {
            if (preg_match('/<(.*)>; rel="(.*)"/i', \trim($relation, ','), $match) === 1) {
                /** @var string[] $match */
                if (3 === count($match) && $match[2] === $type) {
                    return $match[1];
                }
            }
        }

        return null;
    }
}
