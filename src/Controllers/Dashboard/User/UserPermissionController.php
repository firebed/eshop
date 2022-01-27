<?php

namespace Eshop\Controllers\Dashboard\User;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\User\User;
use Illuminate\View\View;

class UserPermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Manage users');
    }

    public function __invoke(User $user): View
    {
        return $this->view('user.permission.index', compact('user'));
    }
}
