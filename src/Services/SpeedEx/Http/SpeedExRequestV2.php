<?php

namespace Eshop\Services\SpeedEx\Http;

use Eshop\Services\SpeedEx\Exceptions\SpeedExException;
use Eshop\Services\SpeedEx\Exceptions\SpeedExSessionExpired;

class SpeedExRequestV2 extends SpeedExRequest
{
    /**
     * @throws SpeedExException
     * @throws SpeedExSessionExpired
     */
    protected function parseResponse($response): mixed
    {
        $response = $response->{$this->action . 'Result'};

        $statusCode = $response->StatusCode;

        if ($statusCode === 1401) { // Session expired
            throw new SpeedExSessionExpired();
        }

        if ($statusCode != 1) {
            $message = $this->parseError($response->Message);
            throw new SpeedExException($response, $message, $statusCode);
        }

        return $response->Result;
    }
}