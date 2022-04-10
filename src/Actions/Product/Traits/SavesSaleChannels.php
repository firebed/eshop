<?php

namespace Eshop\Actions\Product\Traits;

use Eshop\Models\Product\Product;

trait SavesSaleChannels
{
    protected function saveSaleChannels(Product $product, ?array $channel_ids): void
    {
        $channel_ids = is_array($channel_ids) ? $channel_ids : [];
        $product->channels()->sync($channel_ids);
    }
}