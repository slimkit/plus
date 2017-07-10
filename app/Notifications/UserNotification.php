<?php

namespace Zhiyi\Plus\Notifications;

use Zhiyi\Plus\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

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
     * @param \Zhiyi\Plus\Models\User $user
     * @return array
     */
    public function via(User $user): array
    {
        $vias = ['database'];

        if ($user->email) {
            $vias[] = 'mail';
        }

        return $vias;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param \Zhiyi\Plus\Models\User $user
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(User $user)
    {
        return (new MailMessage)->markdown('mails.user_notification', [
            'message' => $this->message,
            'user' => $user,
        ]);
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
