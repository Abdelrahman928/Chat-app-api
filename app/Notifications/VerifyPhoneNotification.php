<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\VonageMessage;

class VerifyPhoneNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['vonage'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toVonage(object $notifiable): VonageMessage
    {
        return (new VonageMessage)
            ->content('Your verification code is: ' . $notifiable->verification_code);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
