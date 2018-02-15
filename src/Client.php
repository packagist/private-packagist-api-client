<?php

namespace PrivatePackagist\ApiClient;

use Http\Client\Common\Plugin;
use Http\Discovery\UriFactoryDiscovery;
use PrivatePackagist\ApiClient\HttpClient\HttpPluginClientBuilder;
use PrivatePackagist\ApiClient\HttpClient\Plugin\PathPrepend;
use PrivatePackagist\ApiClient\HttpClient\Plugin\RequestSignature;

class Client
{
    /** @var HttpPluginClientBuilder */
    private $httpClientBuilder;

    /** @param string $privatePackagistUrl */
    public function __construct(HttpPluginClientBuilder $httpClientBuilder = null, $privatePackagistUrl = null)
    {
        $this->httpClientBuilder = $builder = $httpClientBuilder ?: new HttpPluginClientBuilder();
        $privatePackagistUrl = $privatePackagistUrl ? : 'https://packagist.com';

        $builder->addPlugin(new Plugin\AddHostPlugin(UriFactoryDiscovery::find()->createUri($privatePackagistUrl)));
        $builder->addPlugin(new PathPrepend('/api'));
        $builder->addPlugin(new Plugin\HeaderDefaultsPlugin([
            'User-Agent' => 'php-private-packagist-api (http://github.com/packagist/private-packagist-api)', // @todo
        ]));
    }

    /**
     * @param string $token
     * @param string $secret
     */
    public function authenticate($token, $secret)
    {
        $this->httpClientBuilder->removePlugin(RequestSignature::class);
        $this->httpClientBuilder->addPlugin(new RequestSignature($token, $secret));
    }

    public function customers()
    {
        return new Api\Customers($this);
    }

    public function packages()
    {
        return new Api\Packages($this);
    }

    public function getHttpClient()
    {
        return $this->getHttpClientBuilder()->getHttpClient();
    }

    protected function getHttpClientBuilder()
    {
        return $this->httpClientBuilder;
    }
}
