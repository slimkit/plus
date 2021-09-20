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

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Conversation;
use function Zhiyi\Plus\setting;
use Zhiyi\Plus\Utils\Markdown;

class SystemController extends Controller
{
    /**
     * create a feedback.
     *
     * @author bs<414606094@qq.com>
     *
     * @param  Request  $request
     * @return mixed
     */
    public function createFeedback(Request $request, Conversation $feedback, Carbon $datetime)
    {
        $feedback->type = 'feedback';
        $feedback->content = $request->input('content');
        $feedback->user_id = $request->user()->id;
        $feedback->system_mark = $request->input('system_mark', ($datetime->timestamp) * 1000);
        $feedback->save();

        return response()->json([
            'message' => ['反馈成功'],
            'data' => $feedback,
        ])->setStatusCode(201);
    }

    /**
     * about us.
     *
     * @author bs<414606094@qq.com>
     *
     * @return html
     */
    public function about()
    {
        if (! is_null(config('site.aboutUs.url'))) {
            return redirect(config('site.aboutUs.url'), 302);
        }
        $body = config('site.aboutUs.content', '');
        $body = preg_replace('/\@\!\[(.*?)\]\((\d+)\)/i', '![$1]('.config('app.url').'/api/v2/files/$2)', $body);
        $content = htmlspecialchars_decode(\Parsedown::instance()->setMarkupEscaped(true)->text($body));

        return view('about', ['content' => $content]);
    }

    /**
     * 注册协议.
     *
     * @author Foreach<791477842@qq.com>
     *
     * @return html
     */
    public function agreement(Markdown $markdown)
    {
        $content = setting('user', 'register-setting', [
            'content' => '# 服务条款及隐私政策',
        ])['content'] ?? '# 服务条款及隐私政策';
        $content = $markdown->toHtml($content);

        return view('agreement', ['content' => $content]);
    }

    /**
     * 获取系统会话列表.
     *
     * @author bs<414606094@qq.com>
     *
     * @param  Request  $request
     * @param  Conversation  $conversationModel
     * @return mixed
     */
    public function getConversations(Request $request, Conversation $conversationModel)
    {
        $uid = $request->user()->id;
        $limit = $request->input('limit', 15);
        $max_id = $request->input('max_id', 0);
        $order = $request->input('order', 0);
        $list = $conversationModel->where(function ($query) use ($uid) {
            $query->where(function ($query) use ($uid) {
                $query->where('type', 'system')->whereIn('to_user_id', [0, $uid]);
            })->orWhere(['type' => 'feedback', 'user_id' => $uid]);
        })
        ->where(function ($query) use ($max_id, $order) {
            if ($max_id > 0) {
                $query->where('id', $order ? '>' : '<', $max_id);
            }
        })
        ->orderBy('id', 'desc')
        ->take($limit)
        ->get();

        return response()->json($list)->setStatusCode(200);
    }
}
