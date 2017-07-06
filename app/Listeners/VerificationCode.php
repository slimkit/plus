<?php

namespace Zhiyi\Plus\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Events\NotificationSent;
use Zhiyi\Plus\Models\VerificationCode as VerificationCodeModel;

class VerificationCode implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Notifications\Events\NotificationSent $event
     * @return void
     */
    public function handle(NotificationSent $event)
    {
        if ($this->validate($notifiable = $event->notifiable)) {
            $notifiable->state = 1;
            $notifiable->save();
        }
    }

    /**
     * Failed handle the event.
     *
     * @param \Illuminate\Notifications\Events\NotificationSent $event
     * @param mixed $exception
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function failed(NotificationSent $event, $exception)
    {
        if ($this->validate($notifiable = $event->notifiable)) {
            $notifiable->state = 2;
            $notifiable->save();
        }

        throw $exception;
    }

    /**
     * Validate the event notifiable instance of verification code model.
     *
     * @param mixed $notifiable
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function validate($notifiable): bool
    {
        return $notifiable instanceof VerificationCodeModel;
    }
}
