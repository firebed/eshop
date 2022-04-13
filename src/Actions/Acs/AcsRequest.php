<?php

namespace Eshop\Actions\Acs;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AcsRequest
{
    private const ENDPOINT = "https://webservices.acscourier.net/ACSRestServices/api/ACSAutoRest";

    protected string $action = "";

    public function request(array $params)
    {
        $params = [
            "ACSAlias"           => $this->action,
            "ACSInputParameters" => array_merge([
                "Company_ID"       => api_key('Company_ID'),
                "Company_Password" => api_key('Company_Password'),
                "User_ID"          => api_key('User_ID'),
                "User_Password"    => api_key('User_Password'),
                "User_locals"      => api_key('User_locals')
            ], $params)
        ];

        $response = Http::withHeaders(['AcsApiKey' => api_key('AcsApiKey')])->post(self::ENDPOINT, $params);
        if (!$response->ok()) {
            return $response->status();
        }

        $response = $response->json();
        
        $hasErrors = $response['ACSExecution_HasError'];
        $errorMessage = $response['ACSExecutionErrorMessage'];

        $results = $response['ACSOutputResponce'];
        
        $valueOutput = $results['ACSValueOutput'];
        $tableOutput = $results['ACSTableOutput']['Table_Data'] ?? [];
        
        return [$valueOutput, $tableOutput];
    }
}