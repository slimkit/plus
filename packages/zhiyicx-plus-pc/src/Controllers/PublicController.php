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

class PublicController extends BaseController
{
    /**
     * 获取打赏列表.
     *
     * @return mixed
     */
    public function rewards(Request $request)
    {
        $type = $request->query('type');
        $post_id = $request->query('post_id');
        if ($request->getinfo) {
            $data['temp'] = true;
            $params = [
                'limit' => $request->query('limit', 15),
                'offset' => $request->query('offset', 0),
            ];
            switch ($type) {
                case 'group-posts':
                        $data['app'] = '帖子';
                        $data['rewards'] = api('GET', '/api/v2/plus-group/group-posts/'.$post_id.'/rewards', $params);
                    break;

                case 'news':
                        $data['app'] = '资讯';
                        $data['rewards'] = api('GET', '/api/v2/news/'.$post_id.'/rewards', $params);
                    break;

                case 'answer':
                        $data['app'] = '回答';
                        $data['rewards'] = api('GET', '/api/v2/question-answers/'.$post_id.'/rewarders', $params);
                    break;

                default:
                    $data['app'] = '动态';
                    $data['rewards'] = api('GET', '/api/v2/feeds/'.$post_id.'/rewards', $params);
                    break;
            }
            $html = view('pcview::templates.rewards', $data, $this->PlusData)->render();

            return response()->json([
                'data' => $html,
                'after' => 0,
                'count' => count($data['rewards']) ?? 0,
            ]);
        }

        return view('pcview::templates.rewards', compact('type', 'post_id'), $this->PlusData);
    }
}
