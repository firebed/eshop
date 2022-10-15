<?php

namespace Eshop\Livewire\Dashboard\Notification;

use Eshop\Models\Notification;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationsTable extends Component
{
    use WithPagination;
    
    public bool $showModal = false;

    private Notification $activeNotification;

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

        return view('eshop::dashboard.notification.wire.notifications-table', [
            'notifications'      => $notifications,
            'activeNotification' => $this->activeNotification ?? null
        ]);
    }
}