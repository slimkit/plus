<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Notifications\Channels;

use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Notifications\Notification;
use Overtrue\EasySms\EasySms;

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
     * @return array|void
     * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
     * @throws \Overtrue\EasySms\Exceptions\NoGatewayAvailableException
     */
    public function send($notifiable, Notification $notification)
    {
        if ((! $to = $notifiable->routeNotificationFor('sms'))) {
            return;
        }

        $message = $notification->toSms($notifiable, $this->sms->getConfig());

        return $this->sms->send($to, $message);
    }
}
