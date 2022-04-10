<?php

namespace Eshop\Actions;

use Eshop\Models\User\User;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class ReportError
{
    public function handle(string $subject, string $message): void
    {
        $me = User::find(1);

        Mail::raw($message, static function (Message $message) use ($me, $subject) {
            $message->to($me->email, $me->fullname)
                ->subject($subject);
        });
    }
}