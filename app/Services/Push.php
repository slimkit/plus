<?php

namespace Zhiyi\Plus\Services;

use JPush\Client;

class Push
{
    protected $environment = false; // true为生产环境

    public function push($alert, $audience, $extras = [])
    {
        $appkey = env('JPUSH_APP_KEY');
        $secret = env('JPUSH_MASTER_SECRET');
        if (! $appkey || ! $secret) {
            return false;
        }

        $client = new Client($appkey, $secret);

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
     * @param  Client $client       [description]
     * @param  [type] $alert        [description]
     * @param  [type] $audience     [description]
     * @param  [type] $notification [description]
     * @return [type]               [description]
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
     * 推送�
     * �部.
     *
     * @author bs<414606094@qq.com>
     * @param  Client $client       [description]
     * @param  [type] $alert        [description]
     * @param  [type] $notification [description]
     * @return [type]               [description]
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
