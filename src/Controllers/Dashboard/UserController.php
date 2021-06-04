<?php

namespace Ecommerce\Controllers\Dashboard;

use App\Models\User;
use Ecommerce\Controllers\Controller;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('dashboard.user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return View
     */
    public function show(User $user): View
    {
        $user->loadCount([
            'carts' => fn($q) => $q->submitted(),
            'carts as cancelled_carts_count' => fn($q) => $q->whereStatusId(6),
            'carts as rejected_carts_count' => fn($q) => $q->whereStatusId(7),
            'carts as returned_carts_count' => fn($q) => $q->whereStatusId(8)
        ]);

        $user->loadSum([
            'carts' => fn($q) => $q->submitted(),
            'carts as cancelled_carts_sum_total' => fn($q) => $q->whereStatusId(6),
            'carts as rejected_carts_sum_total' => fn($q) => $q->whereStatusId(7),
            'carts as returned_carts_sum_total' => fn($q) => $q->whereStatusId(8)
        ], 'total');

        $user->a = $user->carts_count - ($user->cancelled_carts_count + $user->returned_carts_count + $user->returned_carts_count);
        $user->b = $user->carts_sum_total - ($user->cancelled_carts_sum_total + $user->rejected_carts_sum_total + $user->returned_carts_sum_total);

        return view('dashboard.user.show', compact('user'));
    }
}
