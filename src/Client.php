<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient;

use Http\Client\Common\Plugin;
use Http\Discovery\Psr17FactoryDiscovery;
use PrivatePackagist\ApiClient\HttpClient\HttpPluginClientBuilder;
use PrivatePackagist\ApiClient\HttpClient\Message\ResponseMediator;
use PrivatePackagist\ApiClient\HttpClient\Plugin\ExceptionThrower;
use PrivatePackagist\ApiClient\HttpClient\Plugin\PathPrepend;
use PrivatePackagist\ApiClient\HttpClient\Plugin\RequestSignature;
use PrivatePackagist\ApiClient\HttpClient\Plugin\TrustedPublishingTokenExchange;
use PrivatePackagist\OIDC\Identities\TokenGenerator;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Client
{
    /** @var HttpPluginClientBuilder */
    private $httpClientBuilder;
    /** @var ResponseMediator */
    private $responseMediator;
    /** @var LoggerInterface */
    private $logger;

    /** @param string $privatePackagistUrl */
    public function __construct(?HttpPluginClientBuilder $httpClientBuilder = null, $privatePackagistUrl = null, ?ResponseMediator $responseMediator = null, ?LoggerInterface  $logger = null)
    {
        $this->httpClientBuilder = $builder = $httpClientBuilder ?: new HttpPluginClientBuilder();
        $privatePackagistUrl = $privatePackagistUrl ? : 'https://packagist.com';
        $this->responseMediator = $responseMediator ? : new ResponseMediator();
        $this->logger = $logger ? : new NullLogger();

        $builder->addPlugin(new Plugin\AddHostPlugin(Psr17FactoryDiscovery::findUriFactory()->createUri($privatePackagistUrl)));
        $builder->addPlugin(new PathPrepend('/api'));
        $builder->addPlugin(new Plugin\RedirectPlugin());
        $headers = [
            'User-Agent' => 'php-private-packagist-api (https://github.com/packagist/private-packagist-api-client)',
        ];
        if ($apiClientVersion = $this->getApiClientVersion()) {
            $headers['API-CLIENT-VERSION'] = $apiClientVersion;
        }
        $builder->addPlugin(new Plugin\HeaderDefaultsPlugin($headers));
        $builder->addPlugin(new ExceptionThrower($this->responseMediator));
    }

    /**
     * @param string $key
     * @param string $secret
     */
    public function authenticate(
        #[\SensitiveParameter]
        $key,
        #[\SensitiveParameter]
        $secret
    ) {
        $this->httpClientBuilder->removePlugin(RequestSignature::class);
        $this->httpClientBuilder->addPlugin(new RequestSignature($key, $secret));
    }

    public function authenticateWithTrustedPublishing(string $organizationUrlName, string $packageName)
    {
        $this->httpClientBuilder->removePlugin(TrustedPublishingTokenExchange::class);
        $this->httpClientBuilder->addPlugin(new TrustedPublishingTokenExchange($organizationUrlName, $packageName, $this->getHttpClientBuilder(), new TokenGenerator($this->logger, $this->getHttpClientBuilder()->getHttpClientWithoutPlugins())));
    }

    public function credentials()
    {
        return new Api\Credentials($this, $this->responseMediator);
    }

    public function teams()
    {
        return new Api\Teams($this, $this->responseMediator);
    }

    public function customers()
    {
        return new Api\Customers($this, $this->responseMediator);
    }

    /**
     * @deprecated Use Client::suborganizations instead
     */
    #[\Deprecated('Use Client::suborganizations instead', '1.16.1')]
    public function projects()
    {
        return new Api\Subrepositories($this, $this->responseMediator);
    }

    /**
     * @deprecated Use Client::suborganizations instead
     */
    #[\Deprecated('Use Client::suborganizations instead', '1.38.0')]
    public function subrepositories()
    {
        return new Api\Subrepositories($this, $this->responseMediator);
    }

    public function suborganizations()
    {
        return new Api\Suborganizations($this, $this->responseMediator);
    }

    public function organization()
    {
        return new Api\Organization($this, $this->responseMediator);
    }

    public function packages()
    {
        return new Api\Packages($this, $this->responseMediator);
    }

    public function securityIssues()
    {
        return new Api\SecurityIssues($this, $this->responseMediator);
    }

    public function jobs()
    {
        return new Api\Jobs($this, $this->responseMediator);
    }

    public function mirroredRepositories()
    {
        return new Api\MirroredRepositories($this, $this->responseMediator);
    }

    public function tokens()
    {
        return new Api\Tokens($this, $this->responseMediator);
    }

    public function synchronizations()
    {
        return new Api\Synchronizations($this, $this->responseMediator);
    }

    public function vendorBundles()
    {
        return new Api\VendorBundles($this, $this->responseMediator);
    }

    public function getHttpClient()
    {
        return $this->getHttpClientBuilder()->getHttpClient();
    }

    public function getResponseMediator()
    {
        return $this->responseMediator;
    }

    protected function getHttpClientBuilder()
    {
        return $this->httpClientBuilder;
    }

    private function getApiClientVersion()
    {
        try {
            return \Composer\InstalledVersions::getVersion('private-packagist/api-client');
        } catch (\OutOfBoundsException $exception) {
            return null;
        }
    }
}
