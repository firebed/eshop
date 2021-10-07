<?php

namespace Eshop\Controllers\Dashboard\User;

use Eshop\Controllers\Controller;
use Eshop\Models\User;
use Illuminate\View\View;

class UserPermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Manage users');
    }
    
    public function __invoke(User $user): View
    {
        return view('eshop::dashboard.user.permission.index', compact('user'));
    }
}
