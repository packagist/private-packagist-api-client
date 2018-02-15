<?php

namespace PrivatePackagist\ApiClient\HttpClient;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Http\Message\RequestFactory;

class HttpPluginClientBuilder
{
    /** @var HttpClient */
    private $httpClient;
    /** @var HttpMethodsClient */
    private $pluginClient;
    /** @var MessageFactory */
    private $requestFactory;
    /** @var Plugin[] */
    private $plugins = [];

    public function __construct(HttpClient $httpClient = null, RequestFactory $requestFactory = null)
    {
        $this->httpClient = $httpClient ?: HttpClientDiscovery::find();
        $this->requestFactory = $requestFactory ?: MessageFactoryDiscovery::find();
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
