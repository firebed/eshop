<?php

namespace Eshop\Models\Product;

use Eshop\Models\Lang\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * Class CategoryChoice
 * @package App\Models\Product
 *
 * @property integer          id
 * @property integer          category_property_id
 * @property string           slug
 * @property integer          position
 *
 * @property Collection       products
 * @property CategoryProperty property
 *
 * @mixin Builder
 */
class CategoryChoice extends Model
{
    use HasFactory,
        HasTranslations;

    protected $guarded = [];

    protected array $translatable = ['name'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_properties');
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(CategoryProperty::class, 'category_property_id');
    }
}
