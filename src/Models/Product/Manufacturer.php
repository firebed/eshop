<?php

namespace Eshop\Models\Product;

use Eshop\Models\Media\Traits\HasImages;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Manufacturer
 * @package App\Models\Product
 *
 * @property integer    id
 * @property string     name
 *
 * @property Collection products
 *
 * @mixin Builder
 */
class Manufacturer extends Model
{
    use HasFactory,
        HasImages;

    protected string $disk = 'manufacturers';

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'products');
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
