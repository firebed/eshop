<?php

namespace Eshop\Controllers\Dashboard\User;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\User\User;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Manage users');
    }

    public function index(): View
    {
        return $this->view('user.index');
    }

    public function show(User $user): View
    {
        $user->loadCount([
            'carts'                          => fn($q) => $q->submitted(),
            'carts as cancelled_carts_count' => fn($q) => $q->whereStatusId(6),
            'carts as rejected_carts_count'  => fn($q) => $q->whereStatusId(7),
            'carts as returned_carts_count'  => fn($q) => $q->whereStatusId(8)
        ]);

        $user->loadSum([
            'carts'                              => fn($q) => $q->submitted(),
            'carts as cancelled_carts_sum_total' => fn($q) => $q->whereStatusId(6),
            'carts as rejected_carts_sum_total'  => fn($q) => $q->whereStatusId(7),
            'carts as returned_carts_sum_total'  => fn($q) => $q->whereStatusId(8)
        ], 'total');

        $user->a = $user->carts_count - ($user->cancelled_carts_count + $user->returned_carts_count + $user->returned_carts_count);
        $user->b = $user->carts_sum_total - ($user->cancelled_carts_sum_total + $user->rejected_carts_sum_total + $user->returned_carts_sum_total);

        return $this->view('user.show', compact('user'));
    }
}
