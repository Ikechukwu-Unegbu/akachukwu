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

    protected $subject;
    protected $message;
    protected $link;
    protected $icon;

   public function __construct(string $subject, string $message, ?string $link = null, ?string $icon = null)
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->link = $link;
        $this->icon = $icon;
    }

    public function via($notifiable)
    {
        return [OneSignalChannel::class];
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->setSubject($this->subject)
            ->setBody($this->message)
            ->setUrl($this->link)
            ->setIcon($this->icon);
    }


}
