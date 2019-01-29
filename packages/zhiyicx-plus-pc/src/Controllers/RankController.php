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

use Cache;
use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;

class RankController extends BaseController
{
    /**
     * 排行榜.
     * @author ZysD
     * @param  int|int $mold [排行榜类型]
     * @return mixed
     */
    public function index(int $mold = 1)
    {
        $data['mold'] = $mold;

        if ($mold == 1) {
            $data['follower'] = $this->rankCache('follower', '/api/v2/ranks/followers');
            $data['balance'] = $this->rankCache('balance', '/api/v2/ranks/balance');
            $data['income'] = $this->rankCache('income', '/api/v2/ranks/income');
            if ($this->PlusData['config']['bootstrappers']['checkin']) {
                $data['check'] = $this->rankCache('check', '/api/v2/checkin-ranks');
                foreach ($data['check'] as &$v) {
                    $v['extra']['count'] = $v['extra']['checkin_count'];
                }
            }
            $data['experts'] = $this->rankCache('experts', '/api/v2/question-ranks/experts');
            $data['likes'] = $this->rankCache('likes', '/api/v2/question-ranks/likes');
        } elseif ($mold == 2) {
            $data['answers_day'] = $this->rankCache('answers_day', '/api/v2/question-ranks/answers');
            $data['answers_week'] = $this->rankCache('answers_week', '/api/v2/question-ranks/answers', ['type' => 'week']);
            $data['answers_month'] = $this->rankCache('answers_month', '/api/v2/question-ranks/answers', ['type' => 'month']);
        } elseif ($mold == 3) {
            $data['feeds_day'] = $this->rankCache('feeds_day', '/api/v2/feeds/ranks');
            $data['feeds_week'] = $this->rankCache('feeds_week', '/api/v2/feeds/ranks', ['type' => 'week']);
            $data['feeds_month'] = $this->rankCache('feeds_month', '/api/v2/feeds/ranks', ['type' => 'month']);
        } elseif ($mold == 4) {
            $data['news_day'] = $this->rankCache('news_day', '/api/v2/news/ranks');
            $data['news_week'] = $this->rankCache('news_week', '/api/v2/news/ranks', ['type' => 'week']);
            $data['news_month'] = $this->rankCache('news_month', '/api/v2/news/ranks', ['type' => 'month']);
        }

        return view('pcview::rank.index', $data, $this->PlusData);
    }

    /**
     * 排行榜列表.
     * @author ZysD
     * @param  Request $request
     * @return mixed
     */
    public function _getRankList(Request $request)
    {
        $genre = $request->input('genre') ?: '';
        $offset = $request->input('offset') ?: 0;
        $limit = $request->input('limit') ?: 0;
        switch ($genre) {
            case 'follower':
                $tabName = '粉丝数';
                $data = $this->rankCache('follower', '/api/v2/ranks/followers', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'balance':
                $tabName = '';
                $data = $this->rankCache('balance', '/api/v2/ranks/balance', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'income':
                $tabName = '';
                $data = $this->rankCache('income', '/api/v2/ranks/income', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'check':
                $tabName = '签到数';
                $data = $this->rankCache('check', '/api/v2/checkin-ranks', ['offset' => $offset, 'limit' => $limit]);
                foreach ($data as &$v) {
                    $v['extra']['count'] = $v['extra']['checkin_count'];
                }
                break;
            case 'experts':
                $tabName = '';
                $data = $this->rankCache('experts', '/api/v2/question-ranks/experts', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'likes':
                $tabName = '问答点赞量';
                $data = $this->rankCache('likes', '/api/v2/question-ranks/likes', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'answers_day':
                $tabName = '问答量';
                $data = $this->rankCache('answers_day', '/api/v2/question-ranks/answers', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'answers_week':
                $tabName = '问答量';
                $data = $this->rankCache('answers_week', '/api/v2/question-ranks/answers', ['type' => 'week', 'offset' => $offset, 'limit' => $limit]);
                break;
            case 'answers_month':
                $tabName = '问答量';
                $data = $this->rankCache('answers_month', '/api/v2/question-ranks/answers', ['type' => 'month', 'offset' => $offset, 'limit' => $limit]);
                break;
            case 'feeds_day':
                $tabName = '点赞量';
                $data = $this->rankCache('feeds_day', '/api/v2/feeds/ranks', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'feeds_week':
                $tabName = '点赞量';
                $data = $this->rankCache('feeds_week', '/api/v2/feeds/ranks', ['type' => 'week', 'offset' => $offset, 'limit' => $limit]);
                break;
            case 'feeds_month':
                $tabName = '点赞量';
                $data = $this->rankCache('feeds_month', '/api/v2/feeds/ranks', ['type' => 'month', 'offset' => $offset, 'limit' => $limit]);
                break;
            case 'news_day':
                $tabName = '浏览量';
                $data = $this->rankCache('news_day', '/api/v2/news/ranks', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'news_week':
                $tabName = '浏览量';
                $data = $this->rankCache('news_week', '/api/v2/news/ranks', ['type' => 'week', 'offset' => $offset, 'limit' => $limit]);
                break;
            case 'news_month':
                $tabName = '浏览量';
                $data = $this->rankCache('news_month', '/api/v2/news/ranks', ['type' => 'month', 'offset' => $offset, 'limit' => $limit]);
                break;
        }

        $return['count'] = count($data);
        $return['nowPage'] = $offset / $limit + 1;

        $return['html'] = view('pcview::templates.rank_lists', [
            'post' => $data,
            'genre' => $genre,
            'tabName' => $tabName,
            'routes' => $this->PlusData['routes'],
        ])
            ->render();

        return response()->json([
            'status'  => true,
            'data' => $return,
        ]);
    }

    /**
     * 排行榜缓存.
     * @author ZysD
     * @param  string  $key    [键名]
     * @param  string  $url    [api地址]
     * @param  array   $params [参数]
     * @param  int $time   [时间]
     * @param  string  $type   [请求类型]
     * @return mixed
     */
    public function rankCache(string $key, string $url, array $params = [], int $time = 5, string $type = 'GET')
    {
        $offset = isset($params['offset']) ? $params['offset'] : 0;
        Cache::has('rank_by_'.$key.'_offset_'.$offset)
            ? Cache::get('rank_by_'.$key.'_offset_'.$offset)
            : Cache::put('rank_by_'.$key.'_offset_'.$offset, api($type, $url, $params), $time);

        return Cache::get('rank_by_'.$key.'_offset_'.$offset);
    }
}
