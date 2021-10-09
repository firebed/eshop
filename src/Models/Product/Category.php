<?php

namespace Eshop\Models\Product;

use Eshop\Database\Factories\Product\CategoryFactory;
use Eshop\Models\Lang\Traits\HasTranslations;
use Eshop\Models\Media\Traits\HasImages;
use Eshop\Models\Seo\Traits\HasSeo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Class Category
 * @package App\Models\Product
 *
 * @property integer    id
 * @property integer    parent_id
 * @property string     type
 * @property boolean    visible
 * @property boolean    promote
 * @property string     slug
 *
 * @property Category   parent
 * @property Collection children
 * @property Collection products
 * @property Collection properties
 * @property Collection manufacturers
 *
 * @property string     name
 *
 * @method Builder promoted()
 * @method Builder visible()
 * @method Builder root()
 * *
 * @mixin Builder
 */
class Category extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasImages;
    use HasSeo;

    public const FOLDER = 'Folder';
    public const FILE   = 'File';

    protected array  $translatable = ['name'];
    protected string $disk         = 'categories';

    protected $fillable = ['parent_id', 'type', 'name', 'slug', 'visible', 'promote'];

    protected $casts = [
        'visible' => 'bool',
        'promote' => 'bool'
    ];

    /*
    |-----------------------------------------------------------------------------
    | RELATIONS
    |-----------------------------------------------------------------------------
    */

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__);
    }

    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(CategoryProperty::class)->orderBy('position');
    }

    public function manufacturers(): BelongsToMany
    {
        return $this->belongsToMany(Manufacturer::class, 'products');
    }

    /*
    |-----------------------------------------------------------------------------
    | SCOPES
    |-----------------------------------------------------------------------------
    */

    public function scopePromoted(Builder $builder): void
    {
        $builder->where('promote', TRUE);
    }

    public function scopeVisible(Builder $builder): void
    {
        $builder->where('visible', TRUE);
    }

    public function scopeRoot(Builder $builder): void
    {
        $builder->whereNull('parent_id');
    }

    public function scopeFiles(Builder $builder): void
    {
        $builder->where('type', self::FILE);
    }

    public function scopeFolders(Builder $builder): void
    {
        $builder->where('type', self::FOLDER);
    }

    /*
    |-----------------------------------------------------------------------------
    | HELPERS
    |-----------------------------------------------------------------------------
    */

    public function isFile(): bool
    {
        return $this->type === self::FILE;
    }

    public function isFolder(): bool
    {
        return $this->type === self::FOLDER;
    }

    protected function registerImageConversions(): void
    {
        $this->addImageConversion('sm', function ($image) {
            $image->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        });
    }

    public function delete(): ?bool
    {
        $this->load('children');
        $this->children->each->delete();
        return parent::delete();
    }

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }
}
