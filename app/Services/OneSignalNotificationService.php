<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UtilitySubscriptionNotification;

class OneSignalNotificationService
{
    protected CONST ICON = 'images/vastel-icon.png';
    /**
     * Send a OneSignal notification to a user.
     *
     * @param User $user
     * @param string $subject
     * @param string $message
     * @param string|null $link
     * @param string|null $icon
     * @return void
     */
    public function sendToUser(User $user, string $subject, string $message, ?string $link = null, ?string $icon = null)
    {
        $link = $link ?? '/';
        $icon = url(self::ICON);
        Notification::send($user, new UtilitySubscriptionNotification($subject, $message, $link, $icon));
    }

    /**
     * Send a OneSignal notification to multiple users.
     *
     * @param array $users
     * @param string $subject
     * @param string $message
     * @param string|null $link
     * @param string|null $icon
     * @return void
     */
    public function sendToUsers(array $users, string $subject, string $message, ?string $link = null, ?string $icon = null)
    {
        $oneSignalMessage = $this->buildOneSignalMessage($subject, $message, $link, $icon);

        Notification::send($users, new UtilitySubscriptionNotification($oneSignalMessage));
    }

    /**
     * Build a OneSignalMessage object.
     *
     * @param string $subject
     * @param string $message
     * @param string|null $link
     * @param string|null $icon
     * @return OneSignalMessage
     */
    protected function buildOneSignalMessage(string $subject, string $message, ?string $link = null, ?string $icon = null): OneSignalMessage
    {
        $oneSignalMessage = OneSignalMessage::create()
            ->setSubject($subject)
            ->setBody($message);

        if ($link) {
            $oneSignalMessage->setUrl($link);
        }

        $oneSignalMessage->setIcon(url(self::ICON));

        return $oneSignalMessage;
    }
}