<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class MoneyTransferNotification extends Notification
{
    use Queueable;

    protected $amount;
    protected $transferType;
    protected $status;
    protected $recipient;
    protected $newBalance;

    public function __construct($amount, $transferType, $status, $recipient, $newBalance)
    {
        $this->amount = $amount;
        $this->transferType = $transferType;
        $this->status = $status;
        $this->recipient = $recipient;
        $this->newBalance = $newBalance;
    }
    
    public function via($notifiable)
    {
        return [OneSignalChannel::class];
    }

    public function toOneSignal($notifiable)
    {
        $subject = $this->status === true
            ? ($this->transferType === 'intra' ? "💰 Intra-Transfer Successful!" : "💰 Bank Transfer Successful!")
            : ($this->transferType === 'intra' ? "❌ Intra-Transfer Failed!" : "❌ Bank Transfer Failed!");

        $body = $this->status === true
            ? ($this->transferType === 'intra'
                ? "You have successfully transferred ₦{$this->amount} to {$this->recipient}. Your new balance is ₦{$this->newBalance}."
                : "You have successfully transferred ₦{$this->amount} to {$this->recipient}'s bank account. Your new balance is ₦{$this->newBalance}.")
            : ($this->transferType === 'intra'
                ? "We were unable to transfer ₦{$this->amount} to {$this->recipient}. Please try again or contact support."
                : "We were unable to transfer ₦{$this->amount} to {$this->recipient}'s bank account. Please try again or contact support.");

        return OneSignalMessage::create()
            ->setSubject($subject)
            ->setBody($body)
            ->setIcon('https://vastel.dev/images/vastel-logo.svg');
    }
}
