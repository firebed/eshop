<?php

namespace Eshop\Models\Product;

use Eshop\Models\Attachments\Traits\HasAttachments;
use Eshop\Models\Audit\Auditable;
use Eshop\Models\Cart\CartProduct;
use Eshop\Models\Lang\Traits\HasTranslations;
use Eshop\Models\Media\Traits\HasImages;
use Eshop\Models\Product\Collection as ProductCollection;
use Eshop\Models\Product\Traits\HasProductAudit;
use Eshop\Models\Seo\Seo;
use Eshop\Models\Seo\Traits\HasSeo;
use Eshop\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;

/**
 * Class Product
 * @package App\Models\Product
 *
 * @property integer         $id
 * @property integer         $parent_id
 * @property integer         $category_id
 * @property integer         $manufacturer_id
 * @property integer         $unit_id
 * @property bool            $is_physical
 * @property bool            $has_variants
 * @property float           $vat
 * @property int             $stock
 * @property int             $weight
 * @property float           $price
 * @property float           $compare_price
 * @property float           $wholesale_price
 * @property float           $discount
 * @property bool            $visible
 * @property bool            $recent
 * @property bool            $promote
 * @property bool            $available
 * @property ?integer|string $available_gt
 * @property bool            $has_watermark
 * @property bool            $display_stock
 * @property ?integer|string $display_stock_lt
 * @property string          $location
 * @property string          $sku
 * @property string          $mpn
 * @property string          $barcode
 * @property string          $slug
 * @property string          $variants_display
 * @property bool            $preview_variants
 * @property string          $variants_prefix
 * @property Collection      $options
 *
 * @property Product         $parent
 * @property Collection      $variants
 * @property Category        $category
 * @property Manufacturer    $manufacturer
 * @property Unit            $unit
 * @property Seo             $seo
 * @property Collection      $properties
 * @property Collection      $choices
 * @property Collection      $variantTypes
 *
 * @property string          $name
 * @property string          $description
 * @property string          $trademark
 * @property int             $available_stock
 *
 * @property float           $discountAmount
 * @property float           $netValue
 * @property float           $netValueWithoutVat
 *
 * @method Product visible($visible = true)
 * @method Product recent($recent = true)
 * @method Product exceptVariants()
 * @method Product exceptParents()
 * @method Product onlyVariants()
 * @method Product onSale()
 * @method Product filterByManufacturers($manufacturer_ids)
 * @method Product filterByPropertyChoices($choices)
 * @method Product filterByPrice($min_price, $max_price)
 *
 * @mixin Builder
 */
class Product extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use HasTranslations;
    use HasImages;
    use HasAttachments;
    use HasSeo;
    use Searchable;
    use HasProductAudit;

    protected $fillable = [
        'name', 'description', 'category_id', 'manufacturer_id', 'unit_id', 'is_physical', 'vat', 'weight',
        'price', 'compare_price', 'wholesale_price', 'discount', 'stock', 'visible', 'recent', 'promote',
        'display_stock', 'display_stock_lt', 'available', 'available_gt', 'has_watermark', 'location', 'sku',
        'mpn', 'barcode', 'slug', 'has_variants', 'variants_display', 'preview_variants', 'variants_prefix'
    ];

    protected array  $translatable = ['name', 'description'];
    protected string $disk         = 'products';

    protected $casts = [
        'price'            => 'float',
        'compare_price'    => 'float',
        'wholesale_price'  => 'float',
        'discount'         => 'float',
        'has_variants'     => 'bool',
        'is_physical'      => 'bool',
        'visible'          => 'bool',
        'recent'           => 'bool',
        'available'        => 'bool',
        'has_watermark'    => 'bool',
        'stock'            => 'int',
        'weight'           => 'int',
        'display_stock'    => 'bool',
        'preview_variants' => 'bool',
        'promote'          => 'bool',
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
        return $this->belongsToMany(CategoryProperty::class, 'product_properties')
            ->withPivot('category_choice_id')
            ->orderBy('position');
    }

    public function choices(): BelongsToMany
    {
        return $this->belongsToMany(CategoryChoice::class, 'product_properties')
            ->withPivot('category_property_id', 'category_choice_id')
            ->withTimestamps()
            ->orderBy('position');
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
        return $this->belongsToMany(VariantType::class)
            ->using(ProductVariantOption::class)
            ->orderBy('variant_types.id')
            ->withPivot('id', 'slug');
    }

    public function variantOptions(): HasMany
    {
        return $this->hasMany(ProductVariantOption::class);
    }

    public function optionValues($glue = ' '): string
    {
        if ($this->relationLoaded('variantOptions')) {
            return $this->variantOptions->pluck('name')->join($glue ?? ' ');
        }

        return $this->options->pluck('pivot.name')->join($glue ?? ' ');
    }

    public function getOptionValuesAttribute(): string
    {
        return $this->optionValues();
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(ProductCollection::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(CartProduct::class);
    }

    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class)->withPivot('price', 'discount')->withTimestamps();
    }

    public function getTrademark(string $glue = ' '): ?string
    {
        return $this->isVariant()
            ? implode(' ', array_filter([$this->parent->name, $this->optionValues($glue)]))
            : $this->name;
    }

    /*
    |-----------------------------------------------------------------------------
    | SCOPES
    |-----------------------------------------------------------------------------
    */

    public function scopeFilterByPrice(Builder $query, $min_price, $max_price): void
    {
        $query->when(!empty($min_price) || !empty($max_price), function (Builder $q) use ($min_price, $max_price) {
            $q->where('visible', true);
            $q->where(function (Builder $b) use ($min_price, $max_price) {
                $b->where('has_variants', false);
                if (!empty($min_price) && !empty($max_price)) {
                    $b->whereBetween('net_value', [$min_price, $max_price]);
                    return;
                }

                if (empty($max_price)) {
                    $b->where('net_value', '>=', $min_price);
                    return;
                }

                $b->where('net_value', '<=', $max_price);
            });

            $q->orWhere(function (Builder $b) use ($min_price, $max_price) {
                $b->where('has_variants', true);
                $b->whereHas('variants', function (Builder $b) use ($min_price, $max_price) {
                    $b->where('visible', true);
                    if (!empty($min_price) && !empty($max_price)) {
                        $b->whereBetween('net_value', [$min_price, $max_price]);
                        return;
                    }

                    if (empty($max_price)) {
                        $b->where('net_value', '>=', $min_price);
                        return;
                    }

                    $b->where('net_value', '<=', $max_price);
                });
            });
        });
    }

    public function scopeVisible(Builder $builder, $visible = true): void
    {
        $builder->where('visible', $visible);
    }

    public function scopeRecent(Builder $builder, $recent = true): void
    {
        $builder->where('recent', $recent);
    }

    public function scopeExceptVariants(Builder $builder): void
    {
        $builder->whereNull('products.parent_id');
    }

    public function scopeOnlyVariants(Builder $builder): void
    {
        $builder->whereNotNull('products.parent_id');
    }

    public function scopeExceptParents(Builder $builder): void
    {
        $builder->where('has_variants', false);
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

    public function scopeOnSale(Builder $builder): Builder
    {
        return $builder->where(function (Builder $b) {
            $b->where('discount', '>', 0);
            $b->orWhereHas('variants', fn($q) => $q->where('visible', true)->where('discount', '>', 0));
        });
    }

    /*
    |-----------------------------------------------------------------------------
    | ACCESSORS & MUTATORS
    |-----------------------------------------------------------------------------
    */

    public function getTrademarkAttribute(): ?string
    {
        return $this->getTrademark();
    }

    public function getAvailableStockAttribute(): int
    {
        return $this->stock - ($this->available_gt ?: 0);
    }

    public function getNetValueAttribute(): float
    {
        return round($this->price * (1 - $this->discount), 2);
    }

    public function getNetValueWithoutVatAttribute(): float
    {
        return round($this->netValue / (1 + $this->vat), 2);
    }

    public function setAvailableGtAttribute($value): void
    {
        $this->attributes['available_gt'] = blank($value) ? null : $value;
    }

    public function setDisplayStockLtAttribute($value): void
    {
        $this->attributes['display_stock_lt'] = blank($value) ? null : $value;
    }

    public function getPriceForUser(?User $user): float
    {
        if ($user === null || $user->cannot('Is merchant')) {
            return $this->price;
        }
        
        return min(array_filter([$this->wholesale_price, $this->netValue]));
    }

    public function getNetValueForUser(?User $user): float
    {
        if ($user === null || $user->cannot('Is merchant')) {
            return $this->netValue;
        }

        return min(array_filter([$this->wholesale_price, $this->netValue]));
    }
    
    public function getDiscountForUser(?User $user): float
    {
        if ($user === null || $user->cannot('Is merchant')) {
            return $this->discount;
        }

        return 0;
    }


    /*
    |-----------------------------------------------------------------------------
    | OTHER
    |-----------------------------------------------------------------------------
    */

    public function isVariant(): bool
    {
        return $this->parent_id !== null;
    }

    public function isAccessible(): bool
    {
        if ($this->isVariant()) {
            return $this->parent->getAttribute('visible') && $this->parent->getAttribute('available') && $this->getAttribute('visible') && $this->getAttribute('available');
        }

        return $this->getAttribute('visible') && $this->getAttribute('available');
    }

    public function canBeBought(int $quantity = 1, bool $includeParent = true): bool
    {
        if ($includeParent && !$this->isAccessible()) {
            return false;
        }

        return $this->available_gt === null || ($this->stock - $quantity >= $this->available_gt);
    }

    public function canDisplayStock(): bool
    {
        if ($this->parent_id !== null && !$this->parent->display_stock) {
            return false;
        }

        return $this->canBeBought()
            && $this->display_stock
            && $this->stock > 0
            && ($this->display_stock_lt === null || $this->stock <= $this->display_stock_lt);
    }

    public function isOnSale(): bool
    {
        return $this->discount > 0 || $this->price < $this->compare_price;
    }

    public function shouldBeSearchable(): bool
    {
        return $this->isVariant()
            ? $this->getAttribute('visible') && $this->parent->getAttribute('visible')
            : $this->getAttribute('visible');
    }

    public function toSearchableArray(): array
    {
        if ($this->isVariant()) {
            $this->loadMissing('variantOptions.translation');
        }

        return array_filter([
            $this->id,
            $this->sku,
            $this->mpn,
            $this->category->name,
            $this->manufacturer?->name,
            //            $this->parent?->translate('description', 'en'),
            //            $this->parent?->translate('description', 'en'),
            $this->translate('name', 'el'),
            $this->translate('name', 'en'),
            //            $this->translate('description', 'el'),
            //            $this->translate('description', 'en'),
            $this->isVariant() ? $this->parent?->translate('name', 'el') : null,
            $this->isVariant() ? $this->parent?->translate('name', 'en') : null,
            $this->isVariant() ? $this->option_values : null
        ]);
    }

    public function delete(): ?bool
    {
        if ($this->has_variants) {
            $this->variants()->delete(); // Updates deleted_at timestamp
        }

        return parent::delete();
    }

    protected function registerImageConversions(): void
    {
        $this->addImageConversion('md', function ($image) {
            $image->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        });

        $this->addImageConversion('sm', function ($image) {
            $image->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        });

        $this->addImageConversion('xs', function ($image) {
            $image->resize(50, 50, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        });
    }
}
