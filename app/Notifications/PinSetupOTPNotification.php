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
        ->view('emails.pin', [
            'user' => $this->user,
            'otp' => $this->otp,
            'type' => $this->type,
        ])
        ->subject("OTP Request");
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
