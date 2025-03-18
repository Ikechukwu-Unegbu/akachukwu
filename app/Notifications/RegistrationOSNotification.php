<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class RegistrationOSNotification extends Notification
{
    private $subject;
    private $message;

    public function __construct($subject, $message)
    {
        $this->subject = $subject;
        $this->message = $message;
    }

    public function via()
    {
        return [OneSignalChannel::class];
    }

    public function toOneSignal()
    {
        return OneSignalMessage::create()
            ->setSubject($this->subject)
            ->setBody($this->message)
            ->setIcon(url('images/vastel-icon.png'));
    }
}
