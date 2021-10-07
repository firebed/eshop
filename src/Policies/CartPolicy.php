<?php

namespace Eshop\Policies;

use Eshop\Models\Cart\Cart;
use Eshop\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->canAny(['Manage orders', 'Manage assigned orders']);
    }

    public function view(User $user, Cart $cart): bool
    {
        if ($user->can('Manage orders')) {
            return true;
        }
        
        return $user->assignedCarts()->where('cart_id', $cart->id)->exists();
    }

    public function delete(User $user, Cart $cart): bool
    {
        return $user->can('Manage orders');
    }
}
