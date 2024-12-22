<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PinSetupOTPNotification extends Notification
{
    use Queueable;

    public $user;
    public $otp;
    public $type;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $otp, $type)
    {
        $this->user = $user;
        $this->otp = $otp;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("OTP Request")
            ->greeting('Hello ' . $this->user?->name . '!')
            ->line('You requested to ' . ($this->type === 'pin_reset' ? 'reset your PIN' : 'set up your PIN') . ' for your Vastel account.')
            ->line('Your One-Time Password (OTP) is: **' . $this->otp . '**')
            ->line('This OTP is valid for 10 minutes. Please do not share it with anyone for your account security.')
            ->line('If you did not request this action, please ignore this message or contact our support team.')
            ->line('Thank you for choosing Vastel!');
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
