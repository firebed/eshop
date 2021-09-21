<?php

namespace App\Http\Controllers\Account;

use Eshop\Controllers\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PasswordController extends Controller
{
    use WithNotifications;

    public function edit(): Renderable
    {
        return view('account.password.edit');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'old_password' => ['required', 'current_password:web'],
            'password'     => ['required', 'confirmed'],
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        $this->showSuccessNotification(__("eshop::notifications.saved"));

        return back();
    }
}
