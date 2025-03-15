<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeEmail extends Notification
{
    use Queueable;

    public $user;
    public $otp;

    /**
     * Create a new notification instance.
     */
    public function __construct($otp, $user)
    {
        $this->user = $user;
        $this->otp = $otp;   
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
        return (new MailMessage)->view(
            'emails.welcome', [
                'user' => $this->user,
                'otp' => $this->otp,
            ]
        )->with('message', $this);
        // return (new MailMessage)
        //             ->greeting('Hi '.$this->user->name.'!!')
        //             ->line('Your Vastel Account is ready. Thank you for using Vastel')
        //             // ->action('Notification Action', url('/'))
        //             ->line('Your OTP is: '. $this->otp)
        //             ->line('Once again, thank you for using our application!');
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
