<?php

namespace Eshop\Livewire\Dashboard\Notification;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\Payout;
use Eshop\Models\Notification;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationsTable extends Component
{
    use WithPagination;

    public bool $showModal = false;

    private ?Notification $activeNotification = null;

    public function show(Notification $notification)
    {
        if ($notification->viewed_at === null) {
            $notification->viewed_at = now();
            $notification->save();

            $this->emitTo('dashboard.notifications-counter', 'notification_seen');
        }

        $this->activeNotification = $notification;
        $this->showModal = true;
    }

    public function render(): Renderable
    {
        $notifications = Notification::latest('created_at')->paginate(30);

        $carts = collect();
        $payouts = collect();

        if ($this->activeNotification !== null && filled($this->activeNotification->metadata)) {
            $metadata = $this->activeNotification->metadata;

            $keyName = $metadata['keyName'];
            $payouts = collect($metadata['payouts']);
            
            $carts = Cart::select('id', 'total', $keyName)->whereIn($keyName, $payouts->pluck('reference'))->get()->keyBy($keyName);

            foreach ($payouts as $key => $payout) {
                $cart = $carts->get($payout['reference']);

                if ($cart === null) {
                    $payout['error'] = "Δεν βρέθηκε αντίστοιχη παραγγελία στο eshop.";
                } elseif (!floats_equal($cart->total, $payout['total'] + $payout['fees'])) {
                    $payout['error'] ??= "Το σύνολο πληρωμής δεν είναι ίδιο με το σύνολο της παραγγελίας.";
                }

                $payouts->put($key, $payout);
            }
        }

        return view('eshop::dashboard.notification.wire.notifications-table', [
            'notifications'      => $notifications,
            'activeNotification' => $this->activeNotification,
            'payouts'            => $payouts,
            'carts'              => $carts,
        ]);
    }
}