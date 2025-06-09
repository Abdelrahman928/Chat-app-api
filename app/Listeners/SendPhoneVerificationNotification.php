<?php

namespace App\Listeners;

use App\Contracts\MustVerifyPhone;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPhoneVerificationNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        if ($event->user instanceof MustVerifyPhone && !$event->user->hasVerifiedPhone()) {
            $event->user->sendPhoneVerificationNotification();
        }
    }
}
