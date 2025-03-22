<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminDebitUserNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    protected $data;
    public function __construct(array $validatedData)
    {
        $this->data = $validatedData;
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
            ->line('NGN '.$this->data['amount'].'has been deducted from your account by our admin.')
            // ->action('Notification Action', url('/'))
            ->line('Reason:')
            ->line('"'.$this->data['reason'].'"')
            ->line('Thank you for using our application!');
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
