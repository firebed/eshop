<?php

namespace Eshop\Controllers\Dashboard;

use Eshop\Models\Notification;
use Illuminate\Contracts\Support\Renderable;

class NotificationController extends Controller
{
    public function __invoke(): Renderable
    {
        $notifications = Notification::latest()->paginate(30);

        return $this->view('notification.index', compact('notifications'));
    }
}