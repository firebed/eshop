<?php

namespace Eshop\Services\Courier;

use Error;
use Eshop\Models\Cart\Voucher;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class CourierService
{
    //private const ENDPOINT = "https://www.myshipping.gr/api/";
    private const ENDPOINT = "http://127.0.0.1:8000/api/";

    /**
     * @throws Error
     */
    private function get(string $method, array $params = []): mixed
    {
        $response = Http::withToken(api_key('COURIER_APIKEY'))
            ->contentType('application/json')
            ->accept('application/json')
            ->get(self::ENDPOINT . $method, $params);

        if ($response->failed()) {
            throw new Error("Courier: " . $response->json()['message']);
        }

        return $response->json('data');
    }

    /**
     * @throws Error
     */
    private function download(string $method, bool $acceptsJson = true, array $params = []): string|array
    {
        $response = Http::withToken(api_key('COURIER_APIKEY'))
            ->contentType('application/json')
            ->accept($acceptsJson ? 'application/json' : 'application/pdf')
            ->get(self::ENDPOINT . $method, $params);
        //dd($response->body());
        if (!$response->successful()) {
            throw new Error("Courier: " . ($response->json()['message'] ?? 'An error occurred.'));
        }

        return $acceptsJson
            ? $response->json()
            : base64_decode($response->body(), true);
    }

    /**
     * @throws Error
     */
    private function post(string $method, array $params): mixed
    {
        $response = Http::withToken(api_key('COURIER_APIKEY'))
            ->accept('application/json')
            ->post(self::ENDPOINT . $method, $params);
        //dd($response->json());
        if ($response->failed()) {
            throw new Error("Courier: " . $response->json()['message'], $response->status());
        }

        return $response->json('data');
    }

    /**
     * @throws Error
     */
    private function put(string $method, array $params): mixed
    {
        $response = Http::withToken(api_key('COURIER_APIKEY'))
            ->accept('application/json')
            ->put(self::ENDPOINT . $method, $params);
        //dd($response->json());
        if ($response->failed()) {
            throw new Error("Courier: " . $response->json()['message'], $response->status());
        }

        return $response->json('data');
    }

    /**
     * @throws Error
     */
    private function delete(string $method, array $params): mixed
    {
        $response = Http::withToken(api_key('COURIER_APIKEY'))
            ->accept('application/json')
            ->delete(self::ENDPOINT . $method, $params);

        if ($response->failed()) {
            throw new Error("Courier: " . $response->json()['message'], $response->status());
        }

        return $response->json('data');
    }

    /**
     * @throws Error
     */
    public function trace(Voucher $voucher): Collection
    {
        $uuid = $voucher->meta['uuid'] ?? null;

        if (blank($uuid)) {
            throw new Error("Ο κωδικός αποστολής δεν είναι συσχετισμένος με το myShipping.gr");
        }

        return collect($this->get("vouchers/$uuid/trace"));
    }

    public function printVouchers(Collection $vouchers, bool $acceptsJson = true, $options = []): string|array
    {
        return $this->download("vouchers/print", $acceptsJson, [
            'ids'     => $vouchers->pluck('meta.uuid')->toArray(),
            'options' => $options,
        ]);
    }

    public function createVoucher(int $courier, array $data)
    {
        return $this->post("couriers/{$courier}/voucher", $data);
    }

    public function createManualVoucher(array $data)
    {
        return $this->post('vouchers', $data);
    }

    public function updateManualVoucher(Voucher $voucher, array $data)
    {
        $uuid = $voucher->meta['uuid'];
        return $this->put("vouchers/$uuid", $data);
    }

    public function deleteVoucher(Voucher $voucher, bool $propagate = true): void
    {
        if (isset($voucher->meta['uuid']) && filled($uuid = $voucher->meta['uuid'])) {
            $this->delete("vouchers/$uuid", ['propagate' => $propagate]);
        }
    }

    public function shippingServices(Courier $courier, string $country_code)
    {
        return $this->get("shipping-services/$courier->value", [
            'country_code' => $country_code
        ]);
    }
}