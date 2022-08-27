<?php

namespace Eshop\Services\Skroutz\Actions;

use Eshop\Services\Skroutz\Enums\SkroutzRejectOptions;
use Eshop\Services\Skroutz\Exceptions\SkroutzException;

class RejectOrder extends SkroutzRequest
{
    private const ACTION = "reject";

    /**
     * To reject specific line items you need to provide a line_items params
     * which would hold an array of line items. Each line items should have
     * the line item id and the rejection reason_id.
     *
     * In case the rejection reason is limited quantity, an available_quantity
     * attribute should also be provided for the specific line item.
     *
     * The available rejection reason IDs are provided in SkroutzRejectOptions when retrieving a single order.
     *
     * @param string      $skroutzOrderId         The order's id by Skroutz
     * @param array       $line_items             The line items to reject
     * @param string|null $rejection_reason_other A generic reason to reject the whole order,
     *                                            like "Our store is closed for personal reasons"
     *
     * @return bool True on success or false on failure
     *
     * @see SkroutzRejectOptions
     * @throws SkroutzException
     */
    public function handle(string $skroutzOrderId, array $line_items = [], string $rejection_reason_other = null): bool
    {
        if (blank($line_items) && blank($rejection_reason_other)) {
            return false;
        }

        $data = [];
        if (filled($line_items)) {
            $data['line_items'] = $line_items;
        } else {
            $data['rejection_reason_other'] = $rejection_reason_other;
        }

        return $this->post($skroutzOrderId, self::ACTION, $data)->json('data');
    }
}