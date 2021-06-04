<?php

namespace App\Models\Product;

use App\Models\Lang\Traits\HasTranslations;
use App\Models\Media\Traits\HasImages;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Class Product
 * @package App\Models\Product
 *
 * @property integer         id
 * @property integer         parent_id
 * @property integer         category_id
 * @property integer         manufacturer_id
 * @property integer         unit_id
 * @property boolean         has_variants
 * @property double          vat
 * @property double          stock
 * @property double          weight
 * @property double          price
 * @property double          compare_price
 * @property double          discount
 * @property boolean         visible
 * @property boolean         available
 * @property ?integer|string available_gt
 * @property boolean         display_stock
 * @property ?integer|string display_stock_lt
 * @property string          location
 * @property string          sku
 * @property string          barcode
 * @property string          slug
 * @property Collection      options Returns a collection of variant options
 *
 * @property Product         parent
 * @property Collection      variants
 * @property Category        category
 * @property Manufacturer    manufacturer
 * @property Unit            unit
 * @property Collection      properties
 * @property Collection      choices
 * @property Collection      variantTypes
 *
 * @property string          name
 * @property string          description
 *
 * @property float           discountAmount
 * @property float           netValue
 * @property float           netValueWithoutVat
 *
 * @method Builder visible($visible = true)
 * @method Builder exceptVariants()
 * @method Builder onlyVariants()
 * @method Builder filterByManufacturers($manufacturer_ids)
 * @method Builder filterByPropertyChoices($choices)
 * @method Builder filterByPrice($min_price, $max_price)
 *
 * @mixin Builder
 */
class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTranslations;
    use HasImages;

    protected $guarded = [];

    protected $translatable = ['name', 'description'];
    protected $disk         = 'products';

    protected $casts = [
        'price'         => 'float',
        'compare_price' => 'float',
        'discount'      => 'float',
        'vat'           => 'float',
        'visible'       => 'bool',
        'available'     => 'bool',
        'display_stock' => 'bool',
    ];

    /*
    |-----------------------------------------------------------------------------
    | RELATIONS
    |-----------------------------------------------------------------------------
    */

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(CategoryProperty::class, 'product_properties')->withPivot('category_choice_id', 'value')->orderBy('position');
    }

    public function choices(): BelongsToMany
    {
        return $this->belongsToMany(CategoryChoice::class, 'product_properties')->withPivot('category_property_id', 'category_choice_id', 'value')->orderBy('position');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    public function variantTypes(): HasMany
    {
        return $this->hasMany(VariantType::class);
    }

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(VariantType::class)->withPivot('value');
    }

    public function getTradeName(string $glue = ' '): ?string
    {
        return $this->isVariant()
            ? $this->parent->name . ' ' . $this->sku . ' ' . $this->getOptionValuesAttribute($glue)
            : $this->name;
    }

    public function getTradeNameAttribute(): ?string
    {
        return $this->getTradeName();
    }

    public function getOptionValuesAttribute($glue = ' '): string
    {
        return $this->options->pluck('pivot.value')->join($glue);
    }

    /*
    |-----------------------------------------------------------------------------
    | SCOPES
    |-----------------------------------------------------------------------------
    */

    public function scopeVisible(Builder $builder, $visible = TRUE): void
    {
        $builder->where('visible', $visible);
    }

    public function scopeExceptVariants(Builder $builder): void
    {
        $builder->whereNull('products.parent_id');
    }

    public function scopeOnlyVariants(Builder $builder): void
    {
        $builder->whereNotNull('products.parent_id');
    }

    public function scopeFilterByManufacturers(Builder $query, Collection $manufacturer_ids): void
    {
        if ($manufacturer_ids->isNotEmpty()) {
            $query->whereIn('manufacturer_id', $manufacturer_ids);
        }
    }

    public function scopeFilterByPropertyChoices(Builder $query, Collection $property_choices): void
    {
        if ($property_choices->isNotEmpty()) {
            foreach ($property_choices as $choices) {
                $query->whereHas('choices', fn(Builder $q) => $q->whereIn('category_choice_id', $choices->pluck('id')));
            }
        }
    }

    public function scopeFilterByPrice(Builder $query, $min_price, $max_price): void
    {
        $query->when(filled($min_price) || filled($max_price), function (Builder $q) use ($min_price, $max_price) {
            $q->where(function(Builder $b) use ($min_price, $max_price) {
                if (filled($min_price) && filled($max_price)) {
                    $b->whereBetween('net_value', [$min_price, $max_price]);
                    return;
                }

                if (blank($max_price)) {
                    $b->where('net_value', '>=', $min_price);
                    return;
                }

                $b->where('net_value', '<=', $max_price);
            });

            $q->orWhereHas('variants', function (Builder $b) use ($min_price, $max_price) {
                $b->where('visible', TRUE);
                if (filled($min_price) && filled($max_price)) {
                    $b->whereBetween('net_value', [$min_price, $max_price]);
                    return;
                }

                if (blank($max_price)) {
                    $b->where('net_value', '>=', $min_price);
                    return;
                }

                $b->where('net_value', '<=', $max_price);
            });
        });
    }

    /*
    |-----------------------------------------------------------------------------
    | ACCESSORS & MUTATORS
    |-----------------------------------------------------------------------------
    */

    public function getDiscountAmountAttribute(): float
    {
        return number_format($this->price * $this->discount, 2);
    }

    public function getNetValueAttribute(): float
    {
        return number_format($this->price - $this->discountAmount, 2);
    }

    public function getNetValueWithoutVatAttribute(): float
    {
        return number_format($this->netValue / (1 + $this->vat), 2);
    }

    public function getTitleAttribute(): string
    {
        return $this->isVariant() ? implode(' ', array_filter([$this->parent->name, $this->sku, $this->name])) : $this->name;
    }

    public function isVariant(): bool
    {
        return $this->parent_id !== NULL;
    }

    public function setAvailableGtAttribute($value): void
    {
        $this->attributes['available_gt'] = blank($value) ? NULL : $value;
    }

    public function setDisplayStockLtAttribute($value): void
    {
        $this->attributes['display_stock_lt'] = blank($value) ? NULL : $value;
    }


    /*
    |-----------------------------------------------------------------------------
    | OTHER
    |-----------------------------------------------------------------------------
    */
    protected function registerImageConversions(): void
    {
        $this->addImageConversion('sm', function ($image) {
            $image->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        });
    }
}
