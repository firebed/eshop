<?php

namespace Eshop\Livewire\Dashboard\Notification;

use Eshop\Models\Notification;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class NotificationsTable extends Component
{
    public bool $showModal = false;
    
    private Notification $activeNotification;

    public function show(int $id)
    {
        $this->activeNotification = Notification::find($id);
        $this->showModal = true;
    }
    
    public function render(): Renderable
    {
        $notifications = Notification::latest()->paginate(30);
        
        return view('eshop::dashboard.notification.wire.notifications-table', [
            'notifications' => $notifications,
            'activeNotification'  => $this->activeNotification ?? null
        ]);
    }
}