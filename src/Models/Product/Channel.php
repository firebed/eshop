<?php

namespace Eshop\Models\Product;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Category
 * @package App\Models\Product
 *
 * @property integer id
 * @property string style
 * @property string color
 * @property bool $suppress_email_notifications
 *
 * @mixin Builder
 */
class Channel extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['id', 'style', 'color', 'suppress_email_notifications'];

    protected $casts = [
        'suppress_email_notifications' => 'bool'
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('price', 'discount')->withTimestamps();
    }
}
