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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\API2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\PaidNode as PaidNodeModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed as FeedModel;

class FeedPayController extends Controller
{
    /**
     * Set feed comment pay.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\PaidNode $paidNode
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed  $feed
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function commentPaid(
        Request $request,
        ResponseContract $response,
        PaidNodeModel $paidNode,
        FeedModel $feed
    ) {
        $feed->load('commentPaidNode');
        $amount = intval($request->input('amount', 0));
        $user = $request->user();

        if ($user->id !== $feed->user_id) {
            return $response->json(['message' => '你没有权限设置'])->setStatusCode(403);
        } elseif ($feed->commentPaidNode !== null) {
            return $response->json(['message' => '已设置了置顶，不可重复设置'])->setStatusCode(422);
        } elseif (! $amount) {
            return $response->json(['amount' => '评论收费金额不允许为空'])->setStatusCode(422);
        }

        $paidNode->channel = 'feed:comment';
        $paidNode->subject = sprintf('评论动态《%s》', str_limit($feed->feed_content, 100));
        $paidNode->body = $paidNode->subject;
        $paidNode->amount = $amount;
        $paidNode->user_id = $user->id;
        $paidNode->extra = 'one';
        $feed->commentPaidNode()->save($paidNode);

        if (! $paidNode->id) {
            return $response->json(['message' => '设置失败'])->setStatusCode(500);
        }

        return $response->json([
            'message' => '设置成功',
            'paid_node' => $paidNode->id,
        ])->setStatusCode(201);
    }
}
