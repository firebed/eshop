<?php

namespace Eshop\Models\User;

use Eshop\Models\Blog\Blog;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Lang\Traits\FullTextIndex;
use Eshop\Models\Location\Addressable;
use Firebed\Permission\Models\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

/**
 * Class User
 * @package App\Models
 *
 * @property Cart activeCart
 *
 * @mixin Builder
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, Addressable, FullTextIndex, SoftDeletes, Billable, HasRoles;

    public array $match = ['first_name', 'last_name', 'email'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'birthday',
        'gender',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday'          => 'date'
    ];

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function operatingCarts(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class, 'cart_operator')->withPivot('viewed_at');
    }

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    public function searches(): HasMany
    {
        return $this->hasMany(CustomerSearch::class);
    }

    public function activeCart(): HasOne
    {
        return $this->hasOne(Cart::class)->abandoned();
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function delete(): bool
    {
        foreach ($this->companies as $company) {
            $company->delete();
        }

        return $this->addresses()->delete() && parent::delete();
    }

    // This is necessary in order to show addresses in the user's addresses table (admin)
    public function getMorphClass(): string
    {
        return 'user';
    }
}
