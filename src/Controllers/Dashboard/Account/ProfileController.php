<?php

namespace Eshop\Controllers\Dashboard\Account;

use Eshop\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
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

        $data['birthday'] = Carbon::createFromFormat('d/m/Y', $request->input('birthday'));

        auth()->user()->update($data);

        return back()->with('success', TRUE);
    }
}
