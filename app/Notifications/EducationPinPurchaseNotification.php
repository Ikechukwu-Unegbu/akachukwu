<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class EducationPinPurchaseNotification extends Notification
{
    use Queueable;
    protected CONST ICON = "https://vastel.dev/images/vastel-logo.svg";
    protected $status;
    protected $amount;
    protected $message;

    public function __construct($status, $message, $amount)
    {
        $this->status = $status;
        $this->amount = $amount;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return [OneSignalChannel::class];
    }

    public function toOneSignal($notifiable)
    {
        $subject = $this->status
        ? "🎉 Exam PIN Purchase was successful!"
        : "❌ Exam PIN Purchase failed!";

        return OneSignalMessage::create()
            ->setSubject($subject)
            ->setBody($this->message)
            ->icon(self::ICON);
    }
}
