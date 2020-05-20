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

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Advertising;
use Zhiyi\Plus\Models\AdvertisingSpace;

class AdvertisingController extends Controller
{
    /**
     * Get installed ad slot information.
     *
     * @author bs<414606094@qq.com>
     * @param  AdvertisingSpace $space
     * @return mix
     */
    public function index(AdvertisingSpace $space): JsonResponse
    {
        $space = $space->select('id', 'channel', 'space', 'alias', 'allow_type', 'format', 'created_at', 'updated_at')->get();

        return response()->json($space, 200);
    }

    /**
     * 查询某一广告位的广告列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request          $request
     * @param  AdvertisingSpace $space
     * @return mix
     */
    public function advertising(AdvertisingSpace $space)
    {
        $space->load(['advertising' => function ($query) {
            return $query->orderBy('sort', 'asc');
        }]);

        return response()->json($space->advertising, 200);
    }

    /**
     * 批量获取广告列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @return json
     */
    public function batch(Request $request, Advertising $advertisingModel)
    {
        $space = explode(',', $request->query('space'));
        $advertising = $advertisingModel->whereIn('space_id', $space)->orderBy('sort', 'asc')->get();

        return response()->json($advertising, 200);
    }
}
