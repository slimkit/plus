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
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatPinneds;

class NewsController extends BaseController
{
    /**
     * 资讯首页.
     * @param Request $request
     * @return mixed
     * @throws \Throwable
     * @author Foreach
     */
    public function index(Request $request)
    {
        $this->PlusData['current'] = 'news';

        if ($request->isAjax) {
            $type = $request->query('type', 'recommend');
            $params = [
                'recommend' => $type == 'recommend' ? 1 : 0,
                'cate_id' => $type == 'category' ? $request->query('category') : 0,
                'after' => $request->query('after', 0),
            ];

            // 获取资讯列表
            $news['news'] = api('GET', '/api/v2/news', $params);
            $after = last($news['news'])['id'] ?? 0;
            $news['cate_id'] = $params['cate_id'];

            $news['space'] = $this->PlusData['config']['ads_space']['pc:news:list'] ?? [];
            $news['page'] = $request->loadcount;

            // 加入置顶资讯
            if ($params['cate_id']) {
                $topNews = api('GET', '/api/v2/news/categories/pinneds', $params);
                $news['news'] = formatPinneds($news['news'], $topNews, 'id');
            }

            $newsData = view('pcview::templates.news', $news, $this->PlusData)->render();

            return response()->json([
                'status'  => true,
                'data' => $newsData,
                'after' => $after,
                'count' => count($news['news']),
            ]);
        }

        // 资讯分类
        $cates = api('GET', '/api/v2/news/cates');
        $data['cates'] = array_merge($cates['my_cates'], $cates['more_cates']);

        $data['cate_id'] = $request->query('cate_id') ?: 0;

        return view('pcview::news.index', $data, $this->PlusData);
    }

    /**
     * 资讯详情.
     * @param News $news
     * @return mixed
     * @author Foreach
     */
    public function read(News $news)
    {
        $this->PlusData['current'] = 'news';

        // 获取资讯详情
        $news_info = api('GET', '/api/v2/news/'.$news->id);
        $news_info['reward'] = api('GET', '/api/v2/news/'.$news->id.'/rewards/sum');
        $news_info['rewards'] = $news->rewards;
        $news_info['collect_count'] = $news->collections->count();

        // 相关资讯
        $news_rel = api('GET', '/api/v2/news/'.$news->id.'/correlations');

        $data['news'] = $news_info;
        $data['news_rel'] = $news_rel;

        return view('pcview::news.read', $data, $this->PlusData);
    }

    /**
     * 资讯投稿
     * @author ZsyD
     * @param  int $news_id [资讯id]
     * @return mixed
     */
    public function release(int $news_id = 0)
    {
        if ($this->PlusData['config']['bootstrappers']['news']['contribute']['verified'] && ! $this->PlusData['TS']['verified']) {
            abort(403, '未认证用户不能投稿');
        }

        $this->PlusData['current'] = 'news';

        // 资讯分类
        $cates = api('GET', '/api/v2/news/cates');
        $data['cates'] = array_merge($cates['my_cates'], $cates['more_cates']);
        // 标签
        $data['tags'] = api('GET', '/api/v2/tags');

        if ($news_id > 0) {
            $data['data'] = api('GET', '/api/v2/news/'.$news_id);
        }

        return view('pcview::news.release', $data, $this->PlusData);
    }

    /**
     * 文章评论列表.
     * @param Request $request
     * @param int $news_id [资讯id]
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     * @author ZsyD
     */
    public function comments(Request $request, int $news_id)
    {
        $params = [
            'after' => $request->query('after') ?: 0,
        ];

        $comments = api('GET', '/api/v2/news/'.$news_id.'/comments', $params);
        $after = last($comments['comments'])['id'] ?? 0;
        $comments['comments'] = formatPinneds($comments['comments'], $comments['pinneds']);
        $commentData = view('pcview::templates.comment', $comments, $this->PlusData)->render();

        return response()->json([
            'status'  => true,
            'data' => $commentData,
            'after' => $after,
            'count' => count($comments),
        ]);
    }
}
