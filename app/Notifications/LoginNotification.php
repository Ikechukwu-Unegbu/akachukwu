<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalChannel;

use NotificationChannels\OneSignal\OneSignalMessage;
use NotificationChannels\OneSignal\OneSignalWebButton;

class LoginNotification extends Notification
{
    use Queueable;
    
    protected $username;
    
    public function __construct($username)
    {
        $this->username = $username;
    }

    public function via($notifiable):array
    {
        return [OneSignalChannel::class];
    }

    public function toOneSignal($notifiable)
    {
        $subject = "👋 Welcome back, {$this->username}!";
        $body = "You have successfully logged in to your account at " . now()->format('h:i A, M d, Y') . ". If this wasn't you, please contact support immediately.";
        return OneSignalMessage::create()
            ->setSubject($subject)
            ->setBody($body)
            ->setIcon('https://vastel.dev/images/vastel-logo.svg'); 
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
