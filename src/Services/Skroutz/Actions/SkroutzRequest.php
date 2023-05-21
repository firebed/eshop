<?php

namespace Eshop\Services\Skroutz\Actions;

use Eshop\Services\Skroutz\Exceptions\SkroutzException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class SkroutzRequest
{
    private const ENDPOINT = "https://api.skroutz.gr/merchants/ecommerce/orders/";

    private const ACCEPT = "application/vnd.skroutz+json; version=3.0";

    /**
     * @throws SkroutzException
     */
    public function get(string $orderId): PromiseInterface|Response
    {
        $response = $this->request()->get(self::ENDPOINT . "$orderId");

        $this->checkResponse($response);

        return $response;
    }

    /**
     * @throws SkroutzException
     */
    public function post(string $orderId, string $action, array $data): PromiseInterface|Response
    {
        $response = $this->request()
            ->contentType('application/json; charset=utf-8')
            ->post(self::ENDPOINT . "$orderId/$action", $data);

        $this->checkResponse($response);

        return $response;
    }

    /**
     * @throws SkroutzException
     */
    public function upload(string $orderId, string $action, array $data): PromiseInterface|Response
    {
        $response = $this->request()
            ->contentType('multipart/form-data')
            ->post(self::ENDPOINT . "$orderId/$action", $data);

        $this->checkResponse($response);

        return $response;
    }

    /**
     * @throws SkroutzException
     */
    private function checkResponse($response): void
    {
        if ($response->failed()) {
            throw new SkroutzException($response->json('errors'));
        }
    }

    private function request(): PendingRequest
    {
        $token = api_key('SKROUTZ_API_TOKEN');

        return Http::withToken($token)->accept(self::ACCEPT);
    }
}