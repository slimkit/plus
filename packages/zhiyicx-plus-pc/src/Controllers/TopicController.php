<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;
use Illuminate\Http\Request;

class TopicController extends BaseController
{

    /**
     * constructor
     *
     * @author mutoe <mutoe@foxmail.com>
     */
    public function __construct()
    {
        parent::__construct();
        $this->PlusData['current'] = 'topic';
    }

    /**
     * 话题首页
     *
     * @author mutoe <mutoe@foxmail.com>
     */
    public function index(Request $request)
    {
        $data['type'] = $request->query('type') ?? 'hot';
        if ($request->isAjax) {
            $after = $request->query('after') ?? 0;
            $data['topics'] = api('GET', '/api/v2/feed/topics', [
                'limit' => 8,
                'index' => $after,
            ]);

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
     * 话题详情
     *
     * @author mutoe <mutoe@foxmail.com>
     * @param Request $request
     * @param int $topic_id
     */
    public function detail(Request $request, int $topic_id)
    {
        // 获取话题详情
        $data['topic'] = api('GET', '/api/v2/feed/topics/' . $topic_id);

        // 获取话题创建者信息
        $data['creator'] = api('GET', '/api/v2/users/' . $data['topic']['creator_user_id']);

        // 获取话题下动态列表
        $data['list'] = api('GET', '/api/v2/feed/topics/' . $topic_id . '/feeds');

        // 获取话题参与者
        $participants = api('GET', '/api/v2/feed/topics/' . $topic_id . '/participants');
        $data['participants'] = api('GET', '/api/v2/users', ['id' => implode(',', $participants)]);

        // 获取热门话题
        $data['list_hot'] = api('GET', '/api/v2/feed/topics', ['limit' => 8, 'only' => 'hot']);

        return view('pcview::topic.detail', $data, $this->PlusData);
    }

    /**
     * 创建话题
     *
     * @author mutoe <mutoe@foxmail.com>
     * @param Request $request
     */
    public function create(Request $request)
    {
        return view('pcview::topic.create', [], $this->PlusData);
    }

    /**
     * 编辑话题
     *
     * @author mutoe <mutoe@foxmail.com>
     * @param Request $request
     * @param integer $topic_id
     */
    public function edit(Request $request, int $topic_id)
    {
        $data['topic'] = api('GET', '/api/v2/feed/topics/' . $topic_id);

        return view('pcview::topic.edit', $data, $this->PlusData);
    }

}
