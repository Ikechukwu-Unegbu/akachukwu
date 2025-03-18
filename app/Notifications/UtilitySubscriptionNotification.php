<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use NotificationChannels\OneSignal\OneSignalWebButton;

class UtilitySubscriptionNotification extends Notification
{
    use Queueable;

    protected $oneSignalMessage;

    public function __construct(OneSignalMessage $oneSignalMessage)
    {
        $this->oneSignalMessage = $oneSignalMessage;
    }

     public function via($notifiable)
    {
        return ['OneSignal'];
    }

    public function toOneSignal($notifiable)
    {
        return $this->oneSignalMessage;
    }
}
