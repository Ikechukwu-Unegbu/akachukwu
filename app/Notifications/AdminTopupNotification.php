<?php

namespace App\Notifications;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdminTopupNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $data;
    public function __construct(array $validated)
    {
        $this->data = $validated;
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
                    ->subject('Your Vastel wallet has been credited')
                    ->greeting('Hi ' . Str::title($notifiable->username) . ',')
                    ->line('Your Vastel wallet has been credited with NGN ' . number_format($this->data['amount']))
                    ->line('Remark: ' . $this->data['reason'])
                    ->line('You can now use your balance to buy data, airtime, pay bills, save, or transfer to others.')
                    ->line('')
                    ->line('🎉 Earn More with Referrals!')
                    ->line('Invite your friends to join Vastel and get rewarded when they buy data.')
                    ->line('')
                    ->line('Share your referral link in the app to start earning!')
                    ->line('')
                    ->line(line: 'Thank you for choosing Vastel — your world of possibilities.')
                    ->line('Warm regards,')
                    ->salutation('The Vastel Team');
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
