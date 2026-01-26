<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\HttpClient;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Http\Message\RequestFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class HttpPluginClientBuilder
{
    /** @var ClientInterface */
    private $httpClient;
    /** @var HttpMethodsClient|null */
    private $pluginClient;
    /** @var RequestFactory|RequestFactoryInterface */
    private $requestFactory;
    /** @var StreamFactoryInterface */
    private $streamFactory;
    /** @var Plugin[] */
    private $plugins = [];

    /**
     * @param RequestFactory|RequestFactoryInterface|null $requestFactory
     * @param StreamFactoryInterface|null $streamFactory
     */
    public function __construct(
        ?ClientInterface $httpClient = null,
        $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null
    ) {
        $requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        if ($requestFactory instanceof RequestFactory) {
            // Use same format as symfony/deprecation-contracts.
            @trigger_error(sprintf(
                'Since %s %s: %s is deprecated, use %s instead.',
                'private-packagist/api-client',
                '1.36.0',
                RequestFactory::class,
                RequestFactoryInterface::class
            ), \E_USER_DEPRECATED);
        } elseif (!$requestFactory instanceof RequestFactoryInterface) {
            /** @var mixed $requestFactory value unknown; set to mixed, prevent PHPStan complaining about guard clauses */
            throw new \TypeError(sprintf(
                '%s::__construct(): Argument #2 ($requestFactory) must be of type %s|%s, %s given',
                self::class,
                RequestFactory::class,
                RequestFactoryInterface::class,
                is_object($requestFactory) ? get_class($requestFactory) : gettype($requestFactory)
            ));
        }

        $this->httpClient = $httpClient ?? Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
    }

    public function addPlugin(Plugin $plugin)
    {
        $this->plugins[] = $plugin;
        $this->pluginClient = null;
    }

    /**
     * @param class-string $pluginClass
     */
    public function removePlugin($pluginClass)
    {
        foreach ($this->plugins as $idx => $plugin) {
            if ($plugin instanceof $pluginClass) {
                unset($this->plugins[$idx]);
                $this->pluginClient = null;
            }
        }
    }

    public function getHttpClient()
    {
        if (!$this->pluginClient) {
            $this->pluginClient = new HttpMethodsClient(
                new PluginClient($this->httpClient, $this->plugins),
                $this->requestFactory,
                $this->streamFactory
            );
        }

        return $this->pluginClient;
    }

    public function getHttpClientWithoutPlugins(): HttpMethodsClient
    {
        return new HttpMethodsClient(
            $this->httpClient,
            $this->requestFactory,
            $this->streamFactory
        );
    }

    /**
     * @return RequestFactory|RequestFactoryInterface
     */
    public function getRequestFactory()
    {
        return $this->requestFactory;
    }

    /**
     * @return StreamFactoryInterface
     */
    public function getStreamFactory(): StreamFactoryInterface
    {
        return $this->streamFactory;
    }
}
