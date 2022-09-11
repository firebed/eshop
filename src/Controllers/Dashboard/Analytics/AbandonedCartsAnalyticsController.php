<?php

namespace Eshop\Controllers\Dashboard\Analytics;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartEvent;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class AbandonedCartsAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:View analytics');
    }

    public function __invoke(Request $request): Renderable
    {
        $sent_1 = Cart::whereHas('events', fn($q) => $q->where('action', CartEvent::ABANDONMENT_EMAIL_1))->count();
        $opened_1 = Cart::whereHas('events', fn($q) => $q->where('action', CartEvent::ABANDONMENT_EMAIL_1_VIEWED))->count();
        $resumed_1 = Cart::whereHas('events', fn($q) => $q->where('action', CartEvent::RESUME_ABANDONED_1))->count();
        $submitted_1 = Cart::query()
            ->submitted()
            ->whereHas('events', fn($q) => $q->where('action', CartEvent::RESUME_ABANDONED_1))
            ->whereDoesntHave('events', fn($q) => $q->whereIn('action', [CartEvent::RESUME_ABANDONED_2, CartEvent::RESUME_ABANDONED_3]))
            ->pluck('total');

        $sent_2 = Cart::whereHas('events', fn($q) => $q->where('action', CartEvent::ABANDONMENT_EMAIL_2))->count();
        $opened_2 = Cart::whereHas('events', fn($q) => $q->where('action', CartEvent::ABANDONMENT_EMAIL_2_VIEWED))->count();
        $resumed_2 = Cart::whereHas('events', fn($q) => $q->where('action', CartEvent::RESUME_ABANDONED_2))->count();
        $submitted_2 = Cart::query()
            ->submitted()
            ->whereHas('events', fn($q) => $q->where('action', CartEvent::RESUME_ABANDONED_2))
            ->whereDoesntHave('events', fn($q) => $q->where('action', CartEvent::RESUME_ABANDONED_3))
            ->pluck('total');

        $sent_3 = Cart::whereHas('events', fn($q) => $q->where('action', CartEvent::ABANDONMENT_EMAIL_3))->count();
        $opened_3 = Cart::whereHas('events', fn($q) => $q->where('action', CartEvent::ABANDONMENT_EMAIL_3_VIEWED))->count();
        $resumed_3 = Cart::whereHas('events', fn($q) => $q->where('action', CartEvent::RESUME_ABANDONED_3))->count();
        $submitted_3 = Cart::query()
            ->submitted()
            ->whereHas('events', fn($q) => $q->where('action', CartEvent::RESUME_ABANDONED_3))
            ->pluck('total');

        return $this->view('analytics.abandoned-carts.index', [
            'sent_1' => $sent_1,
            'sent_2' => $sent_2,
            'sent_3' => $sent_3,
            
            'opened_1' => $opened_1,
            'opened_2' => $opened_2,
            'opened_3' => $opened_3,

            'resumed_1' => $resumed_1,
            'resumed_2' => $resumed_2,
            'resumed_3' => $resumed_3,
            
            'submitted_1' => $submitted_1,
            'submitted_2' => $submitted_2,
            'submitted_3' => $submitted_3,
        ]);
    }
}
