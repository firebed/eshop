<?php

namespace Eshop\Services\SpeedEx\Http;

use Error;
use Illuminate\Support\Facades\Cache;
use SoapClient;
use stdClass;
use Throwable;

class SpeedExRequest
{
    private const WSDL = "https://devspdxws.gr/accesspoint.asmx?WSDL"; // Demo
    //private const WSDL = "https://spdxws.gr/accesspoint.asmx?WSDL";
    protected string $action = '';

    private static function getSessionId(): string
    {
        return Cache::remember('speedex-session-id', now()->addHour(), function () {
            $auth = new SoapClient(self::WSDL);

            $session = $auth->CreateSession([
                //'username' => api_key('SpeedEx_Username'),
                //'password' => api_key('SpeedEx_Password'),
                'username' => 'demoapi',
                'password' => 'GOOD-GO-HOME-GUYS',
            ]);

            return $session->sessionId;
        });
    }

    public function request(array $params): null|stdClass
    {
        Cache::forget('speedex-session-id');
        try {
            $this->checkCredentials();

            $client = new SoapClient(self::WSDL, [
                'soap_version' => SOAP_1_2,
                //'cache_wsdl' => WSDL_CACHE_NONE,
                //'encoding' => 'UTF-8',
                //'exceptions' => true,
                //'trace' => 1
            ]);

            $params['sessionID'] = self::getSessionId();

            $response = $client->{$this->action}($params);

            if ($response->returnCode === 1401) {
                Cache::forget('speedex-session-id');
                return $this->request($params);
            }

            return $response;
        } catch (Throwable $t) {
            return null;
        }
    }

    private function checkCredentials(): void
    {
        if (blank(api_key('SpeedEx_Username')) || blank(api_key('SpeedEx_Password'))) {
            throw new Error("Missing SpeedEx credentials.");
        }
    }
}