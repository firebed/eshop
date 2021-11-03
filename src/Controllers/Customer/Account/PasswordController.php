<?php

namespace Eshop\Controllers\Customer\Account;

use Eshop\Controllers\Customer\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    use WithNotifications;

    public function edit(): Renderable
    {
        return $this->view('account.password.edit');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'old_password' => ['required', 'current_password:web'],
            'password'     => ['required', 'confirmed'],
        ]);

        auth()->user()?->update([
            'password' => Hash::make($request->password),
        ]);

        $this->showSuccessNotification(__("eshop::notifications.saved"));

        return back();
    }
}
