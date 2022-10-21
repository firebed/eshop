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
                    'UserAlias'       => 'CourierCenterAPITestUser',//api_key('CourierCenter_UserAlias'),
                    'CredentialValue' => 'CourierCenterAPITestUser',//api_key('CourierCenter_CredentialValue'),
                    'ApiKey'          => 'CourierCenterAPITestKey',//api_key('CourierCenter_ApiKey'),
                ]
            ], $params);
            
            $response = Http::post(self::ENDPOINT . $this->action, $params);

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