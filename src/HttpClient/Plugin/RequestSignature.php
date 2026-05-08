<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Psr\Http\Message\RequestInterface;

class RequestSignature implements Plugin
{
    use Plugin\VersionBridgePlugin;

    const SIGNATURE_VERSION = '2';

    /** @var string */
    private $key;
    /** @var string */
    private $secret;

    /**
     * @param string $key
     * @param string $secret
     */
    public function __construct(
        #[\SensitiveParameter]
        $key,
        #[\SensitiveParameter]
        $secret
    ) {
        if (!$key || !$secret) {
            throw new \InvalidArgumentException('$key and $secret must be set');
        }

        $this->key = $key;
        $this->secret = $secret;
    }

    /**
     * {@inheritdoc}
     */
    protected function doHandleRequest(RequestInterface $request, callable $next, callable $first)
    {
        $params = [
            'key' => $this->key,
            'timestamp' => $this->getTimestamp(),
            'cnonce' => $this->getNonce(),
            'version' => self::SIGNATURE_VERSION,
            'query' => $this->normalizeQueryString($request->getUri()->getQuery()),
        ];

        $content = (string) $request->getBody();
        if ($content) {
            $params['body'] = $content;
        }

        $request = $request->withHeader('Authorization', sprintf(
            'PACKAGIST-HMAC-SHA256 Key=%s, Timestamp=%s, Cnonce=%s, Version=%s, Signature=%s',
            $params['key'],
            $params['timestamp'],
            $params['cnonce'],
            $params['version'],
            $this->generateSignature($request, $params)
        ));

        return $next($request);
    }

    protected function getTimestamp()
    {
        return (int) gmdate('U');
    }

    protected function getNonce()
    {
        return bin2hex(random_bytes(20));
    }

    private function generateSignature(RequestInterface $request, array $params)
    {
        $data = $request->getMethod() . "\n"
            . $request->getUri()->getHost() . "\n"
            . $request->getUri()->getPath() . "\n"
            . $this->normalizeParameters($params);

        return \base64_encode(
            \hash_hmac('sha256', $data, $this->secret, true)
        );
    }

    private function normalizeParameters(array $params)
    {
        uksort($params, 'strcmp');

        return http_build_query($params, '', '&', PHP_QUERY_RFC3986);
    }

    /**
     * @param string $queryString
     * @return string
     */
    private function normalizeQueryString($queryString)
    {
        if ($queryString === '') {
            return '';
        }

        $queryParams = [];
        parse_str($queryString, $queryParams);
        uksort($queryParams, 'strcmp');

        return http_build_query($queryParams, '', '&', PHP_QUERY_RFC3986);
    }
}
