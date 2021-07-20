<?php


namespace Eshop\Database\Seeders\Traits;


use Eshop\Database\Factories\Product\ProductFactory;
use Eshop\Models\Media\Image;
use Eshop\Models\Product\CategoryProperty;

trait HasProducts
{
    private static array $images = [
        'polo_navy' => 'https://c.scdn.gr/images/sku_main_images/015242/15242004/xlarge_20180620130253_ralph_lauren_710680785004_navy.jpeg'
    ];

    public function tShirtsForMen(): ProductFactory
    {
        $size = CategoryProperty::firstWhere('slug', 'megethos');
        $color = CategoryProperty::firstWhere('slug', 'color');

        return $this->name('Polo')
            ->hasAttached($size->choices, ['category_property_id' => $size->id], 'choices')
            ->has(Image::factory()->url(self::$images['polo_navy']));
    }
}