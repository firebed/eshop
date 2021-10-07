<?php

namespace Eshop\Livewire\Dashboard\User;

use Eshop\Models\User;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class UserCartsTable extends Component
{
    use WithPagination;

    public User $user;

    public function render(): View
    {
        $carts = $this->user
            ->carts()
            ->with('status', 'shippingMethod', 'paymentMethod')
            ->orderByRaw('submitted_at IS NOT NULL')
            ->latest('submitted_at')
            ->paginate();

        return view('eshop::dashboard.user.wire.user-carts-table', compact('carts'));
    }
}
