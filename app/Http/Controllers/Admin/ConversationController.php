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

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Conversation;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type');
        $keyword = $request->get('keyword');
        $limit = (int) $request->get('limit', 15);
        $offset = (int) $request->get('offset', 0);

        $query = Conversation::orderBy('id', 'desc')
        ->when(! is_null($type), function ($query) use ($type) {
            $query->where('type', $type);
        })
        ->when(! is_null($keyword), function ($query) use ($keyword) {
            $query->where('content', 'like', sprintf('%%%s%%', $keyword));
        });

        $total = $query->count('id');
        $items = $query->select([
            'id',
            'user_id',
            'to_user_id',
            'type',
            'content',
            'created_at',
        ])
            ->with(['user' => function ($query) {
                $query->select('id', 'name');
            }, 'target' => function ($query) {
                $query->select('id', 'name');
            }])
            ->limit($limit)
            ->offset($offset)
            ->get();

        return response()->json($items, 200, ['x-conversation-total' => $total]);
    }

    public function delete(Conversation $conversation)
    {
        $conversation->delete();

        return response()->json('', 204);
    }
}
