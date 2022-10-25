<?php

namespace Eshop\Services\SpeedEx\Http;

use Error;
use Eshop\Services\SpeedEx\Exceptions\SpeedExException;
use Eshop\Services\SpeedEx\Exceptions\SpeedExSessionExpired;
use Illuminate\Support\Facades\Cache;
use SoapClient;
use SoapFault;
use stdClass;

class SpeedExRequest
{
    private const ENV      = 'dev';
    private const WSDL_DEV = "https://devspdxws.gr/accesspoint.asmx?WSDL";
    private const WSDL     = "https://spdxws.gr/accesspoint.asmx?WSDL";

    protected string $action = '';

    private static SoapClient $client;

    protected static function isProduction(): bool
    {
        return self::ENV === "prod";
    }

    /**
     * @throws SoapFault
     */
    protected static function client(): SoapClient
    {
        $wsdl = self::isProduction() ? self::WSDL : self::WSDL_DEV;

        return self::$client ??= new SoapClient($wsdl, [
            'soap_version' => SOAP_1_2,
            //'cache_wsdl' => WSDL_CACHE_NONE,
            //'encoding' => 'UTF-8',
            //'exceptions' => true,
            //'trace' => 1
        ]);
    }

    private static function getSessionId(): string
    {
        Cache::forget('speedex-session-id');

        return Cache::remember('speedex-session-id', now()->addHour(), function () {
            $credentials = self::isProduction() ? [
                'username' => api_key('SpeedEx_Username'),
                'password' => api_key('SpeedEx_Password'),
            ] : [
                'username' => 'demoapi',
                'password' => 'GOOD-GO-HOME-GUYS',
            ];

            $session = self::client()->CreateSession($credentials);

            return $session->sessionId;
        });
    }

    /**
     * @throws SpeedExException
     */
    public function request(array $params): null|stdClass
    {
        try {
            $this->checkCredentials();

            $params['sessionID'] = self::getSessionId();

            $response = self::client()->{$this->action}($params);

            return $this->parseResponse($response);
        } catch (SpeedExSessionExpired) {
            Cache::forget('speedex-session-id');
            return $this->request($params);
        } catch (SoapFault $ex) {
            throw new SpeedExException(null, $ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    /**
     * @throws SpeedExException
     * @throws SpeedExSessionExpired
     */
    protected function parseResponse($response): mixed
    {
        $statusCode = $response->returnCode;

        if ($statusCode === 1401) { // Session expired
            throw new SpeedExSessionExpired();
        }

        if ($statusCode != 1) {
            $message = $this->parseError($response->returnMessage);
            throw new SpeedExException($response, $message, $statusCode);
        }

        return $response;
    }

    protected function parseError(string $message): string
    {
        $message = str($message);
        if ($message->startsWith('General error.')) {
            return $message->between('.', '.') . '.';
        }

        return $message;
    }

    private function checkCredentials(): void
    {
        if (blank(api_key('SpeedEx_Username')) || blank(api_key('SpeedEx_Password'))) {
            throw new Error("Missing SpeedEx credentials.");
        }
    }
}