<?php

namespace Eshop\Controllers\Dashboard\User;

use Eshop\Controllers\Controller;
use Eshop\Models\User;
use Illuminate\View\View;

class UserPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param User $user
     * @return View
     */
    public function __invoke(User $user): View
    {
        return view('eshop::dashboard.user.permission.index', compact('user'));
    }
}
