<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Exception;

class TrustedPublishingTokenExchangeException extends RuntimeException
{
    /**
     * @param mixed[] $response
     */
    public static function fromResponse(array $response): self
    {
        if (isset($response['status'], $response['message']) && $response['status'] === 'error') {
            return new self($response['message']);
        }

        return new self('Unable to exchange token');
    }
}
