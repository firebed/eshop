<?php

namespace Eshop\Controllers\Dashboard\Intl;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Location\Province;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BulkProvinceController extends Controller
{
    use WithNotifications;

    public function __construct()
    {
        $this->middleware('can:Manage countries');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'ids'   => ['required', 'array', 'exists:provinces,id'],
            'ids.*' => ['required', 'integer'],
        ]);

        Province::whereKey($request->input('ids'))->delete();

        $this->showSuccessNotification(__("eshop::notifications.deleted"));
        return back();
    }
}