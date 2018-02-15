<?php

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
