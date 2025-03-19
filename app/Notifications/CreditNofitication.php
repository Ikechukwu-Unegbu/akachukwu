<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class CreditNofitication extends Notification
{
    use Queueable;

    protected CONST ICON = "https://vastel.dev/images/vastel-logo.svg";
    protected $utility;
    protected $balance;
    protected $amount;

    public function __construct($utility, $amount, $balance)
    {
        $this->utility = $utility;
        $this->amount = $amount;
        $this->balance = $balance;
    }

    public function via($notifiable)
    {
        return [OneSignalChannel::class];
    }

    public function toOneSignal($notifiable)
    {
        $subject = "💰 Refund Processed!";
        $message = "Your wallet has been refunded with ₦". number_format($this->amount) ." for {$this->utility} purchase. Your new balance is #" . number_format($this->balance) . ". Thank you for using Vastel App!";

        return OneSignalMessage::create()
            ->setSubject($subject)
            ->setBody($message)
            ->icon(self::ICON);
    }
}
