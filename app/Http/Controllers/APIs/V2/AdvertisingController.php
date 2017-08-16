<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Advertising;
use Zhiyi\Plus\Models\AdvertisingSpace;

class AdvertisingController extends Controller
{
    /**
     * Get installed ad slot information.
     *
     * @author bs<414606094@qq.com>
     * @param  Request          $request
     * @param  AdvertisingSpace $space
     * @return mix
     */
    public function index(Request $request, AdvertisingSpace $space)
    {
        $space = $space->get();

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
    public function advertising(Request $request, AdvertisingSpace $space)
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
