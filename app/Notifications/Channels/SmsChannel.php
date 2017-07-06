<?php

namespace Zhiyi\Plus\Notifications\Channels;

use Overtrue\EasySms\EasySms;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

class SmsChannel
{
    /**
     * The SMS notification driver.
     *
     * @var \Overtrue\EasySms\EasySms
     */
    protected $sms;

    /**
     * The app.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create the SMS notification channel instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param \Overtrue\EasySms\EasySms $sms
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(ApplicationContract $app, EasySms $sms)
    {
        $this->app = $app;
        $this->sms = $sms;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return \Nexmo\Message\Message
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('sms')) {
            return;
        }

        $message = $notification->toSms($notifiable, $this->sms->getConfig());

        return $this->sms->send($to, $message);
    }
}
