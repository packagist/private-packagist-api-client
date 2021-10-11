<?php

/*
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\HttpClient\Plugin;

class RequestSignatureMock extends RequestSignature
{
    private $timestamp;
    private $nonce;

    public function init($timestamp, $nonce)
    {
        $this->timestamp = $timestamp;
        $this->nonce = $nonce;
    }

    protected function getTimestamp()
    {
        return $this->timestamp;
    }

    protected function getNonce()
    {
        return $this->nonce;
    }
}
