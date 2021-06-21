<?php

namespace Eshop\Livewire\Dashboard\User;

use Eshop\Models\User;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class UserAddressesTable extends Component
{
    use WithPagination;

    public User $user;

    public function render(): View
    {
        $addresses = $this->user
            ->addresses()
            ->with('country')
            ->paginate(3);

        return view('eshop::dashboard.user.wire.user-addresses-table', compact('addresses'));
    }
}
