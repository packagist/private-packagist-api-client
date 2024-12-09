<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Exception;

use Throwable;

class HttpTransportException extends RuntimeException
{
    private $requestUri;

    public function __construct($message = "", $code = 0, $requestUri = "", ?Throwable $previous = null)
    {
        $this->requestUri = $requestUri;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getRequestUri()
    {
        return $this->requestUri;
    }
}
