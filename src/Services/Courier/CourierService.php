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
    private function get(string $method, array $params = [], ?string $key = 'data'): mixed
    {
        $response = Http::withToken(api_key('myShipping_apikey'))
            ->contentType('application/json')
            ->accept('application/json')
            ->get(self::ENDPOINT . $method, $params);
        
        if ($response->failed()) {
            throw new Error("Courier: " . $response->json()['message']);
        }

        return $response->json($key);
    }

    /**
     * @throws Error
     */
    private function post(string $method, array $params, ?string $key = 'data'): mixed
    {
        $response = Http::withToken(api_key('myShipping_apikey'))
            ->accept('application/json')
            ->post(self::ENDPOINT . $method, $params);
        //dd($response->json());
        if ($response->failed()) {
            throw new Error("Courier: " . $response->json()['message'], $response->status());
        }

        return $response->json($key);
    }

    /**
     * @throws Error
     */
    private function put(string $method, array $params): mixed
    {
        $response = Http::withToken(api_key('myShipping_apikey'))
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
        $response = Http::withToken(api_key('myShipping_apikey'))
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
        if (blank($voucher->myshipping_id)) {
            throw new Error("Ο κωδικός αποστολής δεν είναι συσχετισμένος με το myShipping.gr");
        }

        return collect($this->get("vouchers/$voucher->myshipping_id/trace"));
    }

    public function printVouchers(Collection $vouchers, bool $merge = true, $options = []): string|array
    {
        return $this->get("vouchers/print", [
            'ids'     => $vouchers->pluck('myshipping_id')->toArray(),
            'merge'   => $merge,
            'options' => $options,
        ], null);
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
        return $this->put("vouchers/$voucher->myshipping_id", $data);
    }

    public function deleteVoucher(Voucher $voucher, bool $propagate = true): void
    {
        if (filled($uuid = $voucher->myshipping_id)) {
            $this->delete("vouchers/$uuid", ['propagate' => $propagate]);
        }
    }

    public function shippingServices(Courier $courier, string $country_code)
    {
        return $this->get("shipping-services/$courier->value", [
            'country_code' => $country_code
        ]);
    }

    public function submitPendingVouchers()
    {
        return $this->post("vouchers/submit", [], null);
    }

    public function validateArea(string $street, string $number, string $postcode, string $region)
    {
        return $this->get('areas/validate', [
            'street'   => $street,
            'number'   => $number,
            'postcode' => $postcode,
            'region'   => $region
        ], null);
    }

    public function searchAreas(string $term)
    {
        return $this->get('areas/search', compact('term'));
    }
}