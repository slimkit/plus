<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatPinneds;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatRepostable;

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
                $feeds['feeds'] = [];
                $feedinfo = api('GET', '/api/v2/feeds/' . $request->feed_id);
                $feedinfo['comments'] = [];
                array_push($feeds['feeds'], $feedinfo);
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
                    $feeds = api('GET', '/api/v2/feed/topics/' . $topic_id . '/feeds', $params);
                    $data['feeds'] = array_filter($feeds, function ($val) use ($topic_id) {
                        $data['test'] = $val;
                        return $val['id'] !== $topic_id;
                    });
                    $after = last($feeds)['index'] ?? 0;
                } else {
                    // 普通列表
                    $params['type'] = $request->query('type');
                    if ($request->query('type') == 'new' || $request->query('type') == 'follow') { // 最新，关注
                        $params['after'] = $request->query('after') ?: 0;
                    } else { // 热门
                        $params['hot'] = $request->query('hot') ?: 0;
                    }
                    $data = api('GET', '/api/v2/feeds', $params);
                    if ($params['type'] != 'follow') { // 置顶动态
                        $data['feeds'] = formatPinneds($data['feeds'], $data['pinned']);
                    }

                    if ($request->query('type') == 'new' || $request->query('type') == 'follow') {
                        $after = last($data['feeds'])['id'] ?? 0;
                    } else {
                        $after = last($data['feeds'])['hot'] ?? 0;
                    }

                }

                $data['space'] = $this->PlusData['config']['ads_space']['pc:feeds:list'] ?? [];
                $data['page'] = $request->loadcount;
                $data['feeds'] = formatRepostable($data['feeds']);

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
        $data['hot_topics'] = api('GET', '/api/v2/feed/topics', ['only' => 'hot']);

        // 用于 at 某人时的初始关注用户列表
        $data['follow_users'] = api('GET', "/api/v2/user/follow-mutual");

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
    public function read(Request $request, Feed $feed)
    {
        $feedinfo = api('GET', '/api/v2/feeds/' . $feed->id);
        $feedinfo['collect_count'] = $feed->collection->count();
        $feedinfo['rewards'] = $feed->rewards->toArray();
        $data['user'] = $feed->user;
        $feedinfo = formatRepostable([$feedinfo]);
        $data['feed'] = $feedinfo[0];

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
        $after = last($comments['comments'])['id'] ?? 0;
        $comments['comments'] = formatPinneds($comments['comments'], $comments['pinneds']);
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
        $feed = [
            'repostable_type' => $type,
            'repostable_id' => $id,
            'repostable' => [],
        ];

        switch ($type) {
            case 'news':
                $feed['repostable'] = api('GET', "/api/v2/news/{$id}");
                break;
            case 'feeds':
                $feed_list = api('GET', "/api/v2/feeds", ['id' => $id . '']);
                if ($feed_list['feeds'][0] ?? false) {
                    $feed['repostable'] = $feed_list['feeds'][0];
                }
                break;
            case 'groups':
                $feed['repostable'] = api('GET', "/api/v2/plus-group/groups/{$id}");
                break;
            case 'group-posts':
            case 'posts':
                $post = api('GET', "/api/v2/group/simple-posts", ['id' => $id . '']);
                $feed['repostable'] = $post[0] ?? $post;
                if ($feed['repostable']['title'] ?? false) {
                    $feed['repostable']['image'] = null; // 当在转发弹框时不显示引用帖子的图片
                    $feed['repostable']['group'] = api('GET', '/api/v2/plus-group/groups/' . $feed['repostable']['group_id']);
                }
                break;
        }
        $data['feed'] = $feed;

        // 用于添加话题时的初始热门话题列表
        $data['hot_topics'] = api('GET', '/api/v2/feed/topics', ['only' => 'hot']);

        // 用于 at 某人时的初始关注用户列表
        $data['follow_users'] = api('GET', "/api/v2/user/follow-mutual");

        return view('pcview::templates.repostable', $data, $this->PlusData)->render();
    }

}
