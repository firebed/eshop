<?php

namespace Eshop\Controllers\Dashboard\Account;

use Eshop\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function edit(): View
    {
        return view('eshop::customer.account.password.edit');
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

        return back()->with('success', true);
    }
}
