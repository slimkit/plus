<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

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
        if ((! $to = $notifiable->routeNotificationFor('sms')) || (empty($this->sms->getDefaultGateway()['gateways']))) {
            return;
        }

        $message = $notification->toSms($notifiable, $this->sms->getConfig());

        return $this->sms->send($to, $message);
    }
}
