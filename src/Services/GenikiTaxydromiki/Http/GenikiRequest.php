<?php

namespace Eshop\Services\GenikiTaxydromiki\Http;

use Error;
use Illuminate\Support\Facades\Cache;
use SoapClient;
use stdClass;
use Throwable;

class GenikiRequest
{
    private const WSDL = "https://testvoucher.taxydromiki.gr/JobServicesV2.asmx"; // Demo
    //private const WSDL = "https://voucher.taxydromiki.gr/JobServicesV2.asmx";
    protected string       $action    = '';

    private static function getAuthKey(): string
    {
        return Cache::remember('geniki-auth-id', now()->addHour(), function() {
            $auth = new SoapClient(self::WSDL);

            $authentication = $auth->Authenticate([
                'sUsrName' => api_key('Geniki_Username'),
                'sUsrPwd' => api_key('Geniki_Password'),
                'applicationKey' => api_key('Geniki_Application_Key'),
            ]);

            return $authentication->AuthenticateResult->Key;
        });
    }

    public function request(array $params): null|stdClass
    {
        try {
            $this->checkCredentials();

            $client = new SoapClient(self::WSDL);

            $params['sAuthKey'] = self::getAuthKey();

            return $client->{$this->action}($params);
        } catch (Throwable $t) {
            if ($t->getCode() === 11) { // Authentication key expired
                Cache::forget('geniki-auth-id');
                return $this->request($params);
            }

            return null;
        }
    }

    private function checkCredentials(): void
    {
        if (blank(api_key('Geniki_Username')) || blank(api_key('Geniki_Password')) || blank(api_key('Geniki_Application_Key'))) {
            throw new Error("Missing Geniki credentials.");
        }
    }
}