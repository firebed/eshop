<?php

namespace Eshop\Livewire\Dashboard\User;

use Eshop\Models\User;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Firebed\Permission\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class ShowUserPermissions extends Component
{
    use SendsNotifications;
    use AuthorizesRequests;

    public User $user;

    public array $selected_permissions = [];

    public function mount(): void
    {
        $this->selected_permissions = $this->user->permissions()->get()->pluck('id')->map(fn($p) => (string)$p)->all();
    }

    public function getRolesProperty(): Collection|array
    {
        return Role::with('permissions')->where('name', '!=', 'Super Admin')->get();
    }

    public function save(): void
    {
//        $user = auth()->user();
//        if ($user && $user->is($this->user)) {
//            $this->showWarningToast(__("Warning"), __("You cannot change your own permissions."));
//            $this->skipRender();
//            return;
//        }

        $this->user->syncPermissions($this->selected_permissions);
        $this->showSuccessToast(__("Changes saved successfully."));
        $this->skipRender();
    }

    public function render(): View
    {
        return view('eshop::dashboard.user.permission.wire.show-permissions');
    }
}
