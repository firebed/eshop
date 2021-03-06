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
 * @property string     slug
 * @property boolean    visible
 * @property integer    position
 *
 * @property Collection $choices
 *
 * @mixin Builder
 */
class CategoryProperty extends Model
{
    public const TYPE_CHECKBOX = 'checkbox';
    public const TYPE_RADIO    = 'radio';

    use HasFactory;
    use HasTranslations;

    protected array $translatable = ['name'];

    protected $casts = [
        'visible' => 'bool',
    ];

    protected $fillable = ['type', 'name', 'slug', 'visible', 'position'];

    public function isCheckbox(): bool
    {
        return $this->type === self::TYPE_CHECKBOX;
    }

    public function isRadio(): bool
    {
        return $this->type === self::TYPE_RADIO;
    }

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

    /*
    |-----------------------------------------------------------------------------
    | SCOPES
    |-----------------------------------------------------------------------------
    */

    public function scopeVisible(Builder $builder, $visible = true): void
    {
        $builder->where('visible', $visible);
    }
}
