<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\newapi;
use Zhiyi\Plus\Models\AdvertisingSpace;

class FeedController extends BaseController
{
    /**
     * 动态首页/列表
     * @author Foreach
     * @param  Request $request
     * @return mixed
     */
    public function feeds(Request $request)
    {
        if ($request->isAjax) {
            if ($request->query('feed_id')) { // 获取单条微博内容
                $feeds['feeds'] = collect();
                $feed = api('GET', '/api/v2/feeds/' . $request->feed_id);
                $feeds['feeds']->push($feed);
                $feedData = view('pcview::templates.feeds', $feeds, $this->PlusData)->render();

                return response()->json([
                    'status' => true,
                    'data' => $feedData,
                ]);
            } else {
                $topic_id = $request->query('topic_id');
                if ($topic_id) {
                    // 话题下的列表
                    $params = [
                        'index' => (int) $request->query('after') ?: 0,
                    ];
                    $feeds = newapi('GET', '/api/v2/feed/topics/' . $topic_id . '/feeds', $params);
                    $data['feeds'] = array_filter($feeds, function($val) use ($topic_id) {
                        $data['test'] = $val;
                        return $val['id'] !== $topic_id;
                    });
                    $after = $feeds[count($feeds) - 1]['index'] ?? 0;
                } else {
                    // 普通列表
                    $params['type'] = $request->query('type');
                    if ($request->query('type') == 'new') {
                        $params['after'] = $request->query('after') ?: 0;
                    } else {
                        $params['offset'] = $request->query('offset') ?: 0;
                    }
                    $data = api('GET', '/api/v2/feeds', $params);
                    if (!empty($data['pinned']) && $params['type'] != 'follow') { // 置顶动态
                        $data['pinned']->reverse()->each(function ($item, $key) use ($data) {
                            $item->pinned = true;
                            $data['feeds']->prepend($item);
                        });
                    }

                    $feed = $data['feeds'];
                    $after = $feed[count($feed) - 1]['id'] ?? 0;
                }

                $data['space'] = $this->PlusData['config']['ads_space']['pc:feeds:list'] ?? [];
                $data['page'] = $request->loadcount;

                $feedData = view('pcview::templates.feeds', $data, $this->PlusData)->render();

                return response()->json([
                    'status' => true,
                    'data' => $feedData,
                    'after' => $after,
                ]);
            }
        }

        // 渲染模板
        $data['type'] = $request->input('type') ?: 'new';

        // 用于添加话题时的初始热门话题列表
        $data['hot_topics'] = newapi('GET', '/api/v2/feed/topics', ['only' => 'hot']);

        // 用于 at 某人时的初始关注用户列表
        $user_id = $this->PlusData['TS']['id'] ?? 0;
        $data['follow_users'] = api('GET', "/api/v2/users/{$user_id}/followings");

        $this->PlusData['current'] = 'feeds';
        return view('pcview::feed.index', $data, $this->PlusData);
    }

    /**
     * 动态详情
     * @author Foreach
     * @param  Request $request
     * @param  int     $feed_id [动态id]
     * @return mixed
     */
    public function read(Request $request, int $feed_id)
    {
        $feed = api('GET', '/api/v2/feeds/' . $feed_id);
        $feed->collect_count = $feed->collection->count();
        $feed->rewards = $feed->rewards->filter(function ($value, $key) {
            return $key < 10;
        });
        $data['feed'] = $feed;
        $data['user'] = $feed->user;

        if ($feed->repostable_type) {
            switch ($feed->repostable_type) {
                case 'feeds':
                    $data['repostable'] = api('GET', "/api/v2/feeds/{$feed->repostable_id}");
                    break;
                case 'news';
                    $data['repostable'] = api('GET', "/api/v2/news/{$feed->repostable_id}");
                    break;
                case 'groups':
                    $data['repostable'] = api('GET', "/api/v2/plus-group/groups/{$feed->repostable_id}");
                    break;
                case 'posts':
                    $data['repostable'] = api('GET', "/api/v2/plus-group/groups/1/posts/{$feed->repostable_id}");
                    break;
            }
        }

        $this->PlusData['current'] = 'feeds';
        return view('pcview::feed.read', $data, $this->PlusData);
    }

    /**
     * 动态评论列表
     * @author Foreach
     * @param  Request $request
     * @param  int     $feed_id [动态id]
     * @return \Illuminate\Http\JsonResponse
     */
    public function comments(Request $request, int $feed_id)
    {
        $params = [
            'after' => $request->query('after') ?: 0,
        ];

        $comments = api('GET', '/api/v2/feeds/' . $feed_id . '/comments', $params);
        $comment = clone $comments['comments'];
        $after = $comment->pop()->id ?? 0;

        if ($comments['pinneds'] != null) {

            $comments['pinneds']->each(function ($item, $key) use ($comments) {
                $item->top = 1;
                $comments['comments']->prepend($item);
            });
        }
        $commentData = view('pcview::templates.comment', $comments, $this->PlusData)->render();

        return response()->json([
            'status' => true,
            'data' => $commentData,
            'after' => $after,
        ]);
    }

    /**
     * 转发
     */
    public function repostable(Request $request)
    {
        $type = $request->query('type');
        $id = $request->query('id');
        $data = [
            'type' => $type,
            'id' => $id,
        ];

        switch ($type) {
            case 'news':
            $data['news'] = api('GET', "/api/v2/news/{$id}");
            break;
            case 'feeds':
            $data['feeds'] = api('GET', "/api/v2/feeds/{$id}");
            break;
            case 'groups':
            $data['groups'] = api('GET', "/api/v2/plus-group/groups/{$id}");
            break;
            case 'posts':
            $data['posts'] = api('GET', "/api/v2/plus-group/groups/1/posts/{$id}"); // fixme: 少参数，圈子id暂时用1代替，不影响最终结果
            $data['posts']['user'] = api('GET', "/api/v2/users/{$data['posts']['user_id']}");
            break;
        }

        // dd($data);

        return view('pcview::templates.repostable', $data, $this->PlusData)->render();
    }

}
