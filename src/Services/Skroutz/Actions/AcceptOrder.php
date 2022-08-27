<?php

namespace Eshop\Services\Skroutz\Actions;

use Eshop\Services\Skroutz\Exceptions\SkroutzException;

class AcceptOrder extends SkroutzRequest
{
    private const ACTION = "accept";

    /**
     * To accept an order you need to provide the pickup_location
     * and the pickup_window which are required. You can also specify the
     * number_of_parcels which is optional with a default value of 1 if missing.
     *
     * The available param values used are provided in accept_options when retrieving a single order.
     *
     * @param string $skroutzOrderId    The order's id by Skroutz
     * @param string $pickup_location   The pickup location's id. Can be retrieved from RetrieveOrder action.
     * @param int    $pickup_window     The pickup window's id. Can be retrieved from RetrieveOrder action.
     * @param int    $number_of_parcels The number of parcels
     *
     * @return bool True on success or false on failure. If the order is already accepted it return false.
     *
     * @see RetrieveOrder
     * @throws SkroutzException
     */
    public function handle(string $skroutzOrderId, string $pickup_location, int $pickup_window, int $number_of_parcels = 1): bool
    {
        $response = $this->post($skroutzOrderId, self::ACTION, [
            'number_of_parcels' => $number_of_parcels,
            'pickup_location'   => $pickup_location,
            'pickup_window'     => $pickup_window
        ]);

        return $response->json('status');
    }
}