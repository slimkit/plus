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
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatRepostable;

class SearchController extends BaseController
{
    /**
     * 搜索.
     * @author Foreach
     * @param  Request     $request
     * @param  int|int $type     [搜索类型]
     * @param  string      $keywords [关键字]
     * @return mixed
     */
    public function index(int $type = 1, string $keywords = '')
    {
        $data['type'] = $type;
        $data['keywords'] = $keywords;

        return view('pcview::search.index', $data, $this->PlusData);
    }

    /**
     * 搜索获取数据.
     * @author Foreach
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        $type = $request->query('type');
        $limit = $request->query('limit') ?: 9;
        $after = $request->query('after') ?: 0;
        $offset = $request->query('offset') ?: 0;
        $keywords = $request->query('keywords') ?: '';

        switch ($type) {
            case '1':
                $params = [
                    'limit' => $limit,
                    'type' => 'new',
                    'search' => $keywords,
                    'after' => $after,
                ];

                $datas = api('GET', '/api/v2/feeds', $params);
                $datas['feeds'] = formatRepostable($datas['feeds']);
                $data = $datas;
                $after = last($data['feeds'])['id'] ?? 0;
                $html = view('pcview::templates.feeds', $data, $this->PlusData)->render();
                break;
            case '3':
                $params = [
                    'limit' => $limit,
                    'after' => $after,
                    'key' => $keywords,
                ];

                $datas = api('GET', '/api/v2/news', $params);
                $data['news'] = $datas;
                $after = last($data['news'])['id'] ?? 0;
                $html = view('pcview::templates.news', $data, $this->PlusData)->render();

                break;
            case '4':
                $params = [
                    'limit' => $limit,
                    'offset' => $offset,
                    'keyword' => $keywords,
                ];

                $datas = api('GET', '/api/v2/user/search', $params);
                $data['users'] = $datas;
                $html = view('pcview::templates.user', $data, $this->PlusData)->render();
                break;
            case '8':
                $params = [
                    'limit' => $limit,
                    'index' => $after,
                    'q' => $keywords,
                ];
                $datas = api('GET', '/api/v2/feed/topics', $params);
                $data['topics'] = $datas;
                $after = end($datas)['id'] ?? 0;
                $html = view('pcview::templates.feed_topic', $data, $this->PlusData)->render();
                break;
        }

        return response()->json([
            'status' => true,
            'data' => $html,
            'count' => count($datas),
            'after' => $after,
        ]);
    }
}
