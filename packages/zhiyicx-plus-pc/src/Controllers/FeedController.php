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

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatPinneds;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatRepostable;

class FeedController extends BaseController
{
    /**
     * 动态首页/列表.
     * @param Request $request
     * @return mixed
     * @throws Throwable
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author Foreach
     */
    public function feeds(Request $request)
    {
        if ($request->isAjax) {
            if ($request->query('feed_id')) { // 获取单条微博内容
                $feeds['feeds'] = [];
                $feedinfo = api('GET', '/api/v2/feeds/'.$request->feed_id);
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
                    $feeds = api('GET', '/api/v2/feed/topics/'.$topic_id.'/feeds', $params);
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
                    'count' => count($data['feeds']),
                    'after' => $after,
                ]);
            }
        }

        // 渲染模板
        $data['type'] = $request->input('type') ?: 'new';

        // 用于添加话题时的初始热门话题列表
        $data['hot_topics'] = api('GET', '/api/v2/feed/topics', ['only' => 'hot']);

        // 用于 at 某人时的初始关注用户列表
        $data['follow_users'] = api('GET', '/api/v2/user/follow-mutual');

        $this->PlusData['current'] = 'feeds';

        return view('pcview::feed.index', $data, $this->PlusData);
    }

    /**
     * 动态详情.
     * @param Feed $feed
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author Foreach
     */
    public function read(Feed $feed)
    {
        $feedinfo = api('GET', '/api/v2/feeds/'.$feed->id);
        $feedinfo['collect_count'] = $feed->collection->count();
        $feedinfo['rewards'] = api('GET', '/api/v2/feeds/'.$feed->id.'/rewards');
        $data['user'] = $feed->user;
        $feedinfo = formatRepostable([$feedinfo]);
        $data['feed'] = $feedinfo[0];
        $this->PlusData['current'] = 'feeds';

        return view('pcview::feed.read', $data, $this->PlusData);
    }

    /**
     * 动态评论列表.
     * @param Request $request
     * @param int $feed_id [动态id]
     * @return JsonResponse
     * @throws Throwable
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author Foreach
     */
    public function comments(Request $request, int $feed_id)
    {
        $params = [
            'after' => $request->query('after') ?: 0,
        ];

        $comments = api('GET', '/api/v2/feeds/'.$feed_id.'/comments', $params);
        $after = last($comments['comments'])['id'] ?? 0;
        $comments['comments'] = formatPinneds($comments['comments'], $comments['pinneds']);
        $commentData = view('pcview::templates.comment', $comments, $this->PlusData)->render();

        return response()->json([
            'status' => true,
            'count' => count($comments['comments']),
            'data' => $commentData,
            'after' => $after,
        ]);
    }

    /**
     * 转发.
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
                $feed_list = api('GET', '/api/v2/feeds', ['id' => $id.'']);
                if ($feed_list['feeds'][0] ?? false) {
                    $feed['repostable'] = $feed_list['feeds'][0];
                }
                break;
            case 'groups':
                $feed['repostable'] = api('GET', "/api/v2/plus-group/groups/{$id}");
                break;
            case 'group-posts':
            case 'posts':
                $post = api('GET', '/api/v2/group/simple-posts', ['id' => $id.'']);
                $feed['repostable'] = $post[0] ?? $post;
                if ($feed['repostable']['title'] ?? false) {
                    $feed['repostable']['image'] = null; // 当在转发弹框时不显示引用帖子的图片
                    $feed['repostable']['group'] = api('GET', '/api/v2/plus-group/groups/'.$feed['repostable']['group_id']);
                }
                break;
            case 'questions':
                $feed['repostable'] = api('GET', "/api/v2/questions/{$id}");
                break;
            case 'question-answers':
                $feed['repostable'] = api('GET', "/api/v2/question-answers/{$id}");
                break;
        }
        $data['feed'] = $feed;

        // 用于添加话题时的初始热门话题列表
        $data['hot_topics'] = api('GET', '/api/v2/feed/topics', ['only' => 'hot']);

        // 用于 at 某人时的初始关注用户列表
        $data['follow_users'] = api('GET', '/api/v2/user/follow-mutual');

        return view('pcview::templates.repostable', $data, $this->PlusData)->render();
    }
}
