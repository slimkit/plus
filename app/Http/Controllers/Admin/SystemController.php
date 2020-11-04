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

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Jobs\PushMessage;
use Zhiyi\Plus\Models\Conversation;

class SystemController extends Controller
{
    /**
     * 发送系统通知.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse|object
     * @author bs<414606094@qq.com>
     */
    public function pushSystemNotice(Request $request)
    {
        if (! $request->user()->ability('admin:notice:send')) {
            return response()->json([
                'errors' => ['你没有发送系统通知的权限'],
            ])->setStatusCode(403);
        }

        $uids = $request->input('uids', 'all');
        $message = $request->input('message', '');

        $result = $this->sendNotice($uids, $message);

        if (! $result) {
            response()->json(['messages' => '操作失败'])->setStatusCode(500);
        }

        return response()->json(['messages' => '发送成功'])->setStatusCode(201);
    }

    protected function sendNotice($uids, string $message)
    {
        if ($uids === 'all') {
            return $this->saveAllConversation($message); // 全站消息
        }

        return $this->saveConversation($uids, $message); // 个人消息
    }

    protected function saveAllConversation(string $message): bool
    {
        $notice = new Conversation();
        $notice->type = 'system';
        $notice->user_id = 0;
        $notice->to_user_id = 0;
        $notice->content = $message;
        $notice->system_mark = Carbon::now()->timestamp * 1000;

        $notice->save();
        if ($notice->id) {
            $extras = ['action' => 'notice', 'type' => 'system'];
            $alert = $message;
            $alias = 'all';

            dispatch(new PushMessage($alert, $alias, $extras));

            return true;
        }

        return false;
    }

    protected function saveConversation($uids, $message): bool
    {
        if (is_string($uids)) {
            $uids = explode(',', $uids);
        }
        $now = Carbon::now();

        $datas = array_map(function ($uid) use ($message, $now) {
            return ['user_id' => 0, 'type' => 'system', 'to_user_id' => $uid, 'content' => $message, 'created_at' => $now->toDateTimeString(), 'updated_at' => $now->toDateTimeString(), 'system_mark' => ($now->timestamp) * 1000];
        }, $uids);

        Conversation::insert($datas);

        $extras = ['action' => 'notice', 'type' => 'system'];
        $alert = $message;
        $alias = implode(',', $uids);

        dispatch(new PushMessage($alert, $alias, $extras));

        return true;
    }
}
