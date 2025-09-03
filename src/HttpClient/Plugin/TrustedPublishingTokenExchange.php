<?php declare(strict_types=1);

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use PrivatePackagist\ApiClient\HttpClient\HttpPluginClientBuilder;
use PrivatePackagist\OIDC\Identities\TokenGeneratorInterface;
use Psr\Http\Message\RequestInterface;

/**
 * @internal
 */
final class TrustedPublishingTokenExchange implements Plugin
{
    use Plugin\VersionBridgePlugin;

    /** @var string */
    private $organizationUrlName;
    /** @var string */
    private $packageName;
    /** @var HttpPluginClientBuilder $httpPluginClientBuilder */
    private $httpPluginClientBuilder;
    /** @var TokenGeneratorInterface */
    private $tokenGenerator;

    public function __construct(string $organizationUrlName, string $packageName, HttpPluginClientBuilder $httpPluginClientBuilder, TokenGeneratorInterface $tokenGenerator)
    {
        $this->organizationUrlName = $organizationUrlName;
        $this->packageName = $packageName;
        $this->httpPluginClientBuilder = $httpPluginClientBuilder;
        $this->tokenGenerator = $tokenGenerator;
    }

    protected function doHandleRequest(RequestInterface $request, callable $next, callable $first)
    {
        $this->httpPluginClientBuilder->removePlugin(self::class);

        $privatePackagistHttpclient = $this->httpPluginClientBuilder->getHttpClient();
        $audience = json_decode((string) $privatePackagistHttpclient->get('/oidc/audience')->getBody(), true);
        if (!isset($audience['audience'])) {
            throw new \RuntimeException('Unable to get audience');
        }

        $token = $this->tokenGenerator->generate($audience['audience']);
        if (!$token) {
            throw new \RuntimeException('Unable to generate OIDC token');
        }

        $apiCredentials = json_decode((string) $privatePackagistHttpclient->post('/oidc/token-exchange/' . $this->organizationUrlName . '/' . $this->packageName, ['Authorization' => 'Bearer ' . $token->token])->getBody(), true);
        if (!isset($apiCredentials['key'], $apiCredentials['secret'])) {
            throw new \RuntimeException('Unable to exchange token');
        }

        $this->httpPluginClientBuilder->addPlugin($requestSignature = new RequestSignature($apiCredentials['key'], $apiCredentials['secret']));

        return $requestSignature->handleRequest($request, $next, $first);
    }
}
