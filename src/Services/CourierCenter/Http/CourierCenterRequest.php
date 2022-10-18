<?php

namespace Eshop\Services\CourierCenter\Http;

use Error;
use Illuminate\Support\Facades\Http;

class CourierCenterRequest
{
    private const ENDPOINT = "https://platform.courier.gr/ccservice/api/";

    protected string $action = '';

    protected function request(array $params)
    {
        try {
            $this->checkCredentials();

            $params = array_merge([
                'Context' => [
                    'UserAlias'       => api_key('CourierCenter_UserAlias'),
                    'CredentialValue' => api_key('CourierCenter_CredentialValue'),
                    'ApiKey'          => api_key('CourierCenter_ApiKey'),
                ]
            ], $params);
            
            $response = Http::withHeaders(['AcsApiKey' => api_key('AcsApiKey')])->post(self::ENDPOINT . $this->action, $params);

            if (!$response->ok()) {
                return $response->status();
            }

            return $response->json();
        } catch (\Throwable $t) {
            return [];
            //throw $t;
        }
    }

    private function checkCredentials(): void
    {
        if (
            blank(api_key('CourierCenter_UserAlias')) ||
            blank(api_key('CourierCenter_CredentialValue')) ||
            blank(api_key('CourierCenter_ApiKey'))
        ) {
            throw new Error("Missing CourierCenter credentials.");
        }
    }
}