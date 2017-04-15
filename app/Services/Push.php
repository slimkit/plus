<?php

namespace Zhiyi\Plus\Services;

use JPush\Client;

class Push
{
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

        try {
            $client->push()
            ->setOptions(1, null, null, false, null)
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
}
