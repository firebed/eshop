<?php

namespace App\Http\Livewire\Dashboard\User;

use App\Models\User;
use Firebed\Livewire\Traits\WithCustomPaginationView;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class UserAddressesTable extends Component
{
    use WithPagination, WithCustomPaginationView {
        WithCustomPaginationView::paginationView insteadof WithPagination;
    }

    public User $user;

    public function render(): View
    {
        $addresses = $this->user
            ->addresses()
            ->with('country')
            ->paginate(3);

        return view('dashboard.user.livewire.user-addresses-table', compact('addresses'));
    }
}
