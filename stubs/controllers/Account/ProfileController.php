<?php

namespace App\Http\Controllers\Account;

use Eshop\Controllers\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    use WithNotifications;

    public function edit(): Renderable
    {
        return view('account.profile.edit', [
            'user' => auth()->user()
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string'],
            'last_name'  => ['required', 'string'],
            'phone'      => ['nullable', 'string'],
            'email'      => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->ignore(auth()->user())],
            'gender'     => ['nullable', 'string', 'in:Male,Female'],
            'birthday'   => ['nullable', 'date_format:d/m/Y']
        ]);

        if ($request->filled('birthday')) {
            $data['birthday'] = Carbon::createFromFormat('d/m/Y', $request->input('birthday'));
        }

        auth()->user()->update($data);

        $this->showSuccessNotification(__("eshop::notifications.saved"));

        return back();
    }
}
