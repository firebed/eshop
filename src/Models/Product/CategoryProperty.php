<?php

namespace Eshop\Models\Product;

use Eshop\Models\Lang\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class CategoryProperty
 * @package App\Models\Product
 *
 * @property integer    id
 * @property integer    category_id
 * @property string     index
 * @property string     value_restriction
 * @property boolean    visible
 * @property boolean    promote
 * @property boolean    show_caption
 * @property integer    position
 * @property string     slug
 *
 * @property Collection $choices
 *
 * @mixin Builder
 */
class CategoryProperty extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $translatable = ['name'];

    protected $casts = [
        'visible'          => 'bool',
        'promote'          => 'bool',
        'show_caption'     => 'bool',
        'show_empty_value' => 'bool',
        'position'         => 'integer'
    ];

    protected $guarded = [];

    /*
    |-----------------------------------------------------------------------------
    | RELATIONS
    |-----------------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function choices(): HasMany
    {
        return $this->hasMany(CategoryChoice::class)->orderBy('position');
    }

    public function isIndexed(): bool
    {
        return $this->index !== 'None';
    }

    public function isIndexMultiple(): bool
    {
        return $this->index === 'Multiple';
    }

    public function isIndexSimple(): bool
    {
        return $this->index === 'Simple';
    }

    public function isValueRestricted(): bool
    {
        return $this->value_restriction !== 'None';
    }

    public function isValueRestrictionSimple(): bool
    {
        return $this->value_restriction === 'Simple';
    }

    public function isValueRestrictionMultiple(): bool
    {
        return $this->value_restriction === 'Multiple';
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
}
