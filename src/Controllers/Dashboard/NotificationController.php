<?php

namespace Eshop\Controllers\Dashboard;

use Eshop\Models\Notification;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NotificationController extends Controller
{
    public function index(): Renderable
    {
        return $this->view('notification.index');
    }

    public function download(Notification $notification): StreamedResponse
    {
        return Storage::disk('payouts')->download($notification->metadata['attachment']);
    }
}