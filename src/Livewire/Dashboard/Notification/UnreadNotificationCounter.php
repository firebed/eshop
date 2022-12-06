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
        
        if ($count > 0) {
            return "<span class='badge bg-light text-dark rounded-pill'>$count</span>";
        }
        
        return "";
    }
}