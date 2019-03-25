<?php

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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;

class TopicController extends BaseController
{
    /**
     * constructor.
     *
     * @author mutoe <mutoe@foxmail.com>
     */
    public function __construct()
    {
        parent::__construct();
        $this->PlusData['current'] = 'topic';
    }

    /**
     * 话题首页.
     *
     * @author mutoe <mutoe@foxmail.com>
     */
    public function index(Request $request)
    {
        $data['type'] = $request->query('type') ?? 'hot';
        if ($request->isAjax) {
            if ($data['type'] === 'hot') {
                $params = ['only' => 'hot'];
            } else {
                $after = $request->query('after') ?? 0;
                $params = [
                    'limit' => 8,
                    'index' => $after,
                ];
            }
            $data['topics'] = api('GET', '/api/v2/feed/topics', $params);

            $view = view('pcview::templates.feed_topic', $data, $this->PlusData)->render();

            return response()->json([
                'status' => true,
                'data' => $view,
                'count' => count($data['topics']),
                'after' => end($data['topics'])['id'] ?? 0,
            ]);
        }

        $data['list_hot'] = api('GET', '/api/v2/feed/topics', ['only' => 'hot']);
        $data['list'] = $data['list_hot'];

        return view('pcview::topic.index', $data, $this->PlusData);
    }

    /**
     * 话题详情.
     *
     * @author mutoe <mutoe@foxmail.com>
     * @param int $topic_id
     */
    public function detail(int $topic_id)
    {
        // 获取话题详情
        $data['topic'] = api('GET', '/api/v2/feed/topics/'.$topic_id);

        // 获取话题创建者信息
        $data['creator'] = api('GET', '/api/v2/users/'.$data['topic']['creator_user_id']);

        // 获取话题下动态列表
        $data['list'] = api('GET', '/api/v2/feed/topics/'.$topic_id.'/feeds');

        // 获取话题参与者
        $participants = api('GET', '/api/v2/feed/topics/'.$topic_id.'/participants');
        $data['participants'] = api('GET', '/api/v2/users', ['id' => implode(',', $participants)]);

        // 获取热门话题
        $data['list_hot'] = api('GET', '/api/v2/feed/topics', ['limit' => 8, 'only' => 'hot']);

        return view('pcview::topic.detail', $data, $this->PlusData);
    }

    /**
     * 创建话题.
     *
     * @author mutoe <mutoe@foxmail.com>
     */
    public function create()
    {
        return view('pcview::topic.create', [], $this->PlusData);
    }

    /**
     * 编辑话题.
     *
     * @author mutoe <mutoe@foxmail.com>
     * @param Request $request
     * @param int $topic_id
     */
    public function edit(int $topic_id)
    {
        $data['topic'] = api('GET', '/api/v2/feed/topics/'.$topic_id);

        return view('pcview::topic.edit', $data, $this->PlusData);
    }
}
