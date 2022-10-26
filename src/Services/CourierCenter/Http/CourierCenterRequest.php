<?php

namespace Eshop\Services\CourierCenter\Http;

use Error;
use Eshop\Services\CourierCenter\Exceptions\CourierCenterException;
use Illuminate\Support\Facades\Http;

class CourierCenterRequest
{
    private const ENV      = 'dev';
    private const ENDPOINT = "https://platform.courier.gr/ccservice/api/";

    protected string $action = '';

    protected static function isProduction(): bool
    {
        return self::ENV === "prod";
    }

    /**
     * @throws CourierCenterException
     */
    protected function request(array $params)
    {
        $this->checkCredentials();

        $params = array_merge($this->credentials(), $params);
        
        $response = Http::post(self::ENDPOINT . $this->action, $params);
        
        if (!$response->successful()) {
            throw new CourierCenterException(null, $response->reason(), $response->status());
        }

        return $this->parseResponse($response->json());
    }

    /**
     * @throws CourierCenterException
     */
    protected function parseResponse(array $response): array
    {
        $result = $response['Result'];
        
        if ($result !== 'Success') {
            $error = array_shift($response['Errors']);
            throw new CourierCenterException($response, $error['Message']);
        }

        return $response;
    }

    private function credentials(): array
    {
        $credentials = self::isProduction()
            ? [
                'UserAlias'       => api_key('CourierCenter_UserAlias'),
                'CredentialValue' => api_key('CourierCenter_CredentialValue'),
                'ApiKey'          => api_key('CourierCenter_ApiKey')
            ] : [
                'UserAlias'       => 'CourierCenterAPITestUser',
                'CredentialValue' => 'CourierCenterAPITestUser',
                'ApiKey'          => 'CourierCenterAPITestKey',
            ];

        return ['Context' => $credentials];
    }

    private function checkCredentials(): void
    {
        if (
            blank(api_key('CourierCenter_UserAlias')) ||
            blank(api_key('CourierCenter_UserAlias')) ||
            blank(api_key('CourierCenter_CredentialValue')) ||
            blank(api_key('CourierCenter_AccountCode'))
        ) {
            throw new Error("Missing CourierCenter credentials.");
        }
    }
}