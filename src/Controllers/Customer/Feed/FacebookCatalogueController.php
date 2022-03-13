<?php

namespace Eshop\Controllers\Customer\Feed;

use Eshop\Models\Product\Product;
use Illuminate\Http\Response;
use SimpleXMLElement;

class FacebookCatalogueController
{
    /** @noinspection NullPointerExceptionInspection */
    public function __invoke(): Response
    {
        $xml = new SimpleXMLElement("<rss xmlns:g='http://base.google.com/ns/1.0' version='2.0' />");

        $channel = $xml->addChild("channel");
        $channel->addChild("title", config('app.name'));
        $channel->addChild("description", "Facebook catalogue xml feed");

        $products = Product::visible()->exceptParents()->with('category', 'seo', 'image', 'options', 'parent.seo')->get();

        foreach ($products as $product) {
            if (!$product->image) {
                continue;
            }

            $item = $channel->addChild("item");

            $item->addChild("g:id", $product->id, "http://base.google.com/ns/1.0");

            if ($product->parent_id !== null) {
                $item->addChild("g:item_group_id", $product->parent_id, "http://base.google.com/ns/1.0");

                foreach ($product->options as $option) {
                    $name = match ($option->name) {
                        'Χρώμα' => 'color',
                        'Μέγεθος' => 'size',
                        'Υλικό' => 'material',
                        default => null
                    };

                    if ($name !== null) {
                        $item->addChild("g:$name", e($option->pivot->value), "http://base.google.com/ns/1.0");
                    }
                }
            }

            $item->addChild("g:title", e($product->seo->title), "http://base.google.com/ns/1.0");
            $item->addChild("g:description", e($product->isVariant() ? $product->parent->seo->description : $product->seo->description), "http://base.google.com/ns/1.0");
            $item->addChild("g:availability", $product->canBeBought() ? 'in stock' : 'out of stock', "http://base.google.com/ns/1.0");
            $item->addChild("g:condition", 'new', "http://base.google.com/ns/1.0");
            $item->addChild("g:price", $product->price . ' EUR', "http://base.google.com/ns/1.0");
            $item->addChild("g:link", productRoute($product), "http://base.google.com/ns/1.0");
            $item->addChild("g:image_link", e($product->image->url($product->has_watermark ? 'wm' : null)), "http://base.google.com/ns/1.0");
            $item->addChild("g:brand", e($product->mpn ?: $product->sku), "http://base.google.com/ns/1.0");
            $item->addChild("g:google_product_category", 1604, "http://base.google.com/ns/1.0"); // Apparel & Accessories > Clothing
        }

        return response($xml->asXML(), 200, ['Context-Type' => 'application/xml']);
    }
}
