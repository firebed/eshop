<?php

namespace Eshop\Actions\SpeedEx;

use Error;
use SoapClient;
use stdClass;
use Throwable;

class SpeedExRequest
{
    private const WSDL = "https://spdxws.gr/accesspoint.asmx?WSDL";
    private static ?string $sessionId = null;
    protected string $action = '';

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

    public function request(array $params): null|stdClass
    {
        try {
            $this->checkCredentials();

            $client = new SoapClient(self::WSDL);

            $params['sessionID'] = self::auth();

            return $client->{$this->action}($params);
        } catch (Throwable) {
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