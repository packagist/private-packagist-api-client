<?php

/*
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

class HttpPluginClientBuilder
{
    /** @var ClientInterface */
    private $httpClient;
    /** @var HttpMethodsClient|null */
    private $pluginClient;
    /** @var RequestFactoryInterface */
    private $requestFactory;
    /** @var Plugin[] */
    private $plugins = [];

    /**
     * @param ClientInterface|null $httpClient
     * @param RequestFactory|RequestFactoryInterface|null $requestFactory
     */
    public function __construct(ClientInterface $httpClient = null, $requestFactory = null)
    {
        $requestFactory = $requestFactory ?: Psr17FactoryDiscovery::findRequestFactory();
        if ($requestFactory instanceof RequestFactory) {
            trigger_deprecation(
                'private-packagist/api-client',
                '1.35.0',
                '',
                RequestFactory::class,
                RequestFactoryInterface::class
            );
        } elseif (!$requestFactory instanceof RequestFactoryInterface) {
            throw new \TypeError(sprintf(
                '%s::__construct(): Argument #2 ($requestFactory) must be of type %s|%s, %s given',
                self::class,
                RequestFactory::class,
                RequestFactoryInterface::class,
                is_object($requestFactory) ? get_class($requestFactory) : gettype($requestFactory)
            ));
        }

        $this->httpClient = $httpClient ?: Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory;
    }

    public function addPlugin(Plugin $plugin)
    {
        $this->plugins[] = $plugin;
        $this->pluginClient = null;
    }

    /**
     * @param string $pluginClass
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
                $this->requestFactory
            );
        }

        return $this->pluginClient;
    }
}
