<?php

namespace Eshop\Livewire\Dashboard\Notification;

use Eshop\Models\Notification;
use Livewire\Component;

class UnreadNotificationCounter extends Component
{
    protected $listeners = ['notification_seen' => '$refresh'];
    
    public function render(): string
    {
        $count = Notification::whereNull('viewed_at')->count();
        
        return "<span class='badge bg-danger'>$count</span>";
    }
}