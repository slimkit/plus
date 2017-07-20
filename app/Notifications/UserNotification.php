<?php

namespace Zhiyi\Plus\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The notification message.
     *
     * @var \Zhiyi\Plus\Notifications\Messages\UserNotificationMessage
     */
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Messages\UserNotificationMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->message->toArray();
    }
}
