<?php

namespace Eshop\Controllers\Dashboard;

use Eshop\Models\Notification;
use Illuminate\Contracts\Support\Renderable;

class NotificationController extends Controller
{
    public function __invoke(): Renderable
    {
        return $this->view('notification.index');
    }
}