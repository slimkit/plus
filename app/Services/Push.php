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

namespace Zhiyi\Plus\Services;

use JPush\Client;
use Illuminate\Contracts\Config\Repository;

class Push
{
    // 推送环境
    protected $environment;

    protected $appkey;

    protected $secret;

    public function __construct(Repository $config)
    {
        $this->appkey = $config->get('jpush.app_key');
        $this->secret = $config->get('jpush.master_secret');

        $this->environment = $config->get('jpush.environment', false);
    }

    public function push($alert, $audience, $extras = [])
    {
        if (! $this->appkey || ! $this->secret) {
            return false;
        }

        $client = new Client($this->appkey, $this->secret);

        $notification = [
            'extras' => $extras,
        ];

        if ($audience == 'all') {
            return $this->pushAll($client, $alert, $notification);
        }

        return $this->pushAlias($client, $alert, $audience, $notification);
    }

    /**
     * 推送别名.
     *
     * @author bs<414606094@qq.com>
     * @param  Client $client
     * @param  $alert
     * @param  $audience
     * @param  $notification
     * @return array
     */
    protected function pushAlias(Client $client, $alert, $audience, $notification)
    {
        try {
            $client->push()
            ->setOptions(1, null, null, $this->environment, null)
            ->setPlatform('all') //全部平台
            ->addAlias($audience) // 指定用户
            ->message($alert, $notification)
            ->iosNotification($alert, $notification)
            ->send();

            return [
                'code'  => 1,
                'data' => [],
                'message' => '推送成功',
            ];
        } catch (\Exception $e) {
            return [
                'code'  => 0,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Push all.
     *
     * @author bs<414606094@qq.com>
     * @param  Client $client
     * @param  $alert
     * @param  $notification
     * @return array
     */
    protected function pushAll(Client $client, $alert, $notification)
    {
        try {
            $client->push()
            ->setOptions(1, null, null, $this->environment, null)
            ->setPlatform('all') //全部平台
            ->addAllAudience() // 全部用户
            ->message($alert, $notification)
            ->iosNotification($alert, $notification)
            ->send();

            return [
                'code'  => 1,
                'data' => [],
                'message' => '推送成功',
            ];
        } catch (\Exception $e) {
            return [
                'code'  => 0,
                'message' => $e->getMessage(),
            ];
        }
    }
}
