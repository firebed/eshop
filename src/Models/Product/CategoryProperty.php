<?php

namespace Eshop\Models\Product;

use Eshop\Database\Factories\Product\CategoryPropertyFactory;
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
    use HasFactory;
    use HasTranslations;

    protected array $translatable = ['name'];

    protected $casts = [
        'visible' => 'bool',
    ];

    protected $fillable = ['type', 'name', 'slug', 'visible', 'position'];

    public function isCheckbox(): bool
    {
        return $this->type === 'checkbox';
    }

    public function isRadio(): bool
    {
        return $this->type === 'radio';
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

    public function scopeVisible(Builder $builder, $visible = TRUE): void
    {
        $builder->where('visible', $visible);
    }

    protected static function newFactory(): CategoryPropertyFactory
    {
        return CategoryPropertyFactory::new();
    }
}
