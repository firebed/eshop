<?php

namespace Eshop\Actions\SpeedEx;

use Error;
use SoapClient;

class SpeedExRequest
{
    private const WSDL = "https://spdxws.gr/accesspoint.asmx?WSDL";

    protected string $action = '';
    
    private static ?string $sessionId = null;

    public function request(array $params)
    {
        $this->checkCredentials();
        
        $client = new SoapClient(self::WSDL);

        $params['sessionID'] = self::auth();

        return $client->{$this->action}($params);
    }

    private static function auth(): string
    {
        if (filled(self::$sessionId)) {
            return self::$sessionId;
        }
        
        $auth = new SoapClient(self::WSDL);

        $session = $auth->CreateSession([
            'username' => api_key('SpeedEx_Username'),
            'password' => api_key('SpeedEx_Password'),
        ]);
        
        return self::$sessionId = $session->sessionId;
    }

    private function checkCredentials(): void
    {
        if (blank(api_key('SpeedEx_Username')) || blank(api_key('SpeedEx_Password'))) {
            throw new Error("Missing SpeedEx credentials.");
        }
    }
}