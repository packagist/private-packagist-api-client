<?php

namespace PrivatePackagist\ApiClient\HttpClient\Message;

use Psr\Http\Message\ResponseInterface;

class ResponseMediator
{
    /**
     * @return mixed|string
     */
    public function getContent(ResponseInterface $response)
    {
        $body = $response->getBody()->__toString();
        if (strpos($response->getHeaderLine('Content-Type'), 'application/json') === 0) {
            $content = json_decode($body, true);
            if (JSON_ERROR_NONE === json_last_error()) {
                return $content;
            }
        }

        return $body;
    }
}
