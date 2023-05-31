<?php

namespace Eshop\Actions\Product\Traits;

use Eshop\Models\Product\Product;

trait SavesSaleChannels
{
    protected function saveSaleChannels(Product $product, ?array $channel_ids, ?array $pricing): void
    {
        $channel_ids = is_array($channel_ids) ? $channel_ids : [];

        $sync = [];
        foreach ($channel_ids as $id) {
            if (!isset($pricing[$id]) || $pricing[$id]['distinct'] === false) {
                $sync[$id] = [
                    'price'    => null,
                    'discount' => null,
                ];

                continue;
            }

            $sync[$id] = [
                'price'    => $pricing[$id]['price'],
                'discount' => $pricing[$id]['discount']
            ];
        }

        $product->channels()->sync($sync);
    }
}
