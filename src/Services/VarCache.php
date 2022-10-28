<?php

namespace Eshop\Services;

use Illuminate\Support\Facades\Cache;

class VarCache
{
    private const KEY = 'user-variables';

    public static function getKeys(): array
    {
        return [
            'GOOGLE' => [
                'GOOGLE_TRANSLATE_API_KEY' => '',
            ],

            'ΑΑΔΕ myDATA' => [
                'MYDATA_ISSUER_VAT'       => '',
                'MYDATA_ISSUER_COUNTRY'   => '',
                'MYDATA_ISSUER_BRANCH'    => '',
                'MYDATA_ENVIRONMENT'      => '',
                'MYDATA_USER_ID'          => '',
                'MYDATA_SUBSCRIPTION_KEY' => '',
            ],

            'ΛΗΨΗ ΣΤΟΙΧΕΙΩΝ Α.Φ.Μ.' => [
                'GGPS_USERNAME' => '',
                'GGPS_PASSWORD' => '',
            ],

            'ACS' => [
                'AcsApiKey'        => '',
                'Company_ID'       => '',
                'Company_Password' => '',
                'User_ID'          => '',
                'User_Password'    => '',
                'Billing_Code'     => '',
                'User_locals'      => ''
            ],

            'SpeedEx' => [
                'SpeedEx_Username'  => '',
                'SpeedEx_Password'  => '',
                'SpeedEx_Customer'  => '',
                'SpeedEx_Agreement' => ''
            ],

            'CourierCenter' => [
                'CourierCenter_UserAlias'       => '',
                'CourierCenter_CredentialValue' => '',
                'CourierCenter_ApiKey'          => '',
                'CourierCenter_AccountCode'     => ''
            ],

            'Γενική Ταχυδρομική' => [
                'Geniki_Username'        => '',
                'Geniki_Password'        => '',
                'Geniki_Application_Key' => ''
            ],

            'SIMPLIFY' => [
                'SIMPLIFY_ENVIRONMENT'         => '',
                'SIMPLIFY_SANDBOX_PUBLIC_KEY'  => '',
                'SIMPLIFY_SANDBOX_PRIVATE_KEY' => '',
                'SIMPLIFY_LIVE_PUBLIC_KEY'     => '',
                'SIMPLIFY_LIVE_PRIVATE_KEY'    => '',
                'SIMPLIFY_HOSTED_PUBLIC_KEY'   => '',
                'SIMPLIFY_HOSTED_PRIVATE_KEY'  => '',
            ],

            'Skroutz' => [
                'SKROUTZ_API_TOKEN' => ''
            ],
        ];
    }

    public static function fill($saved): array
    {
        $variables = self::getKeys();
        foreach ($variables as $name => $values) {
            foreach ($values as $key => $value) {
                $variables[$name][$key] = $saved[$key] ?? null;
            }
        }

        return $variables;
    }

    public static function remember(array $variables): void
    {
        Cache::forget(self::KEY);
        Cache::rememberForever(self::KEY, static fn() => $variables);
    }

    public static function get($key, $default = null): ?string
    {
        return Cache::get(self::KEY)[$key] ?? $default;
    }

    public static function all($default = null): ?array
    {
        return Cache::get(self::KEY, $default);
    }
}