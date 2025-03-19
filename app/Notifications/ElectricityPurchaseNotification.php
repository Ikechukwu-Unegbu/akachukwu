<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class ElectricityPurchaseNotification extends Notification
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
        ? "🎉 Bill Purchase was successful!"
        : "❌ Bill Purchase failed!";

        return OneSignalMessage::create()
            ->setSubject($subject)
            ->setBody($this->message)
            ->icon(self::ICON);
    }
}
