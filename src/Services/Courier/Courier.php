<?php

namespace Eshop\Services\Courier;

use Error;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class Courier
{
    //private const ENDPOINT = "https://courier.devlyst.com/api/";
    private const ENDPOINT = "http://127.0.0.1:8000/api/";

    /**
     * @throws Error
     */
    private function get(string $method, array $params): mixed
    {
        $response = Http::withToken(api_key('COURIER_APIKEY'))
            ->contentType('application/json')
            ->accept('application/json')
            ->get(self::ENDPOINT . $method, $params);
        
        if ($response->failed()) { 
            throw new Error($response->json()['message']);
        }

        return $response->json();
    }

    /**
     * @throws Error
     */
    private function download(string $method, array $params): string
    {
        $response = Http::withToken(api_key('COURIER_APIKEY'))
            ->contentType('application/json')
            ->accept('application/json')
            ->get(self::ENDPOINT . $method, $params);
        
        if (!$response->successful()) {
            throw new Error($response->json()['message'] ?? 'An error occurred.');
        }

        return base64_decode($response->body());
    }

    /**
     * @throws Error
     */
    private function post(string $method, array $params): mixed
    {
        $response = Http::withToken(api_key('COURIER_APIKEY'))
            ->accept('application/json')
            ->post(self::ENDPOINT . $method, $params);
        
        if ($response->failed()) {
            throw new Error($response->json()['message'], $response->status());
        }

        return $response->json();
    }

    /**
     * @throws Error
     */
    public function trace(Couriers $courier, string $voucher): Collection
    {
        return collect($this->get('vouchers/trace', [
            'courier' => $courier->value,
            'number'  => $voucher
        ]));
    }

    public function printVoucher(array $vouchers): string
    {
        return $this->download('vouchers/print', ['vouchers' => $vouchers]);
    }

    public function createVoucher(array $data)
    {
        return $this->post('vouchers', $data);
    }

    public function cancelVoucher(Couriers $courier, string $voucher)
    {
        return $this->post('vouchers/cancel', [
            'courier' => $courier->value,
            'number'  => $voucher
        ]);
    }
}