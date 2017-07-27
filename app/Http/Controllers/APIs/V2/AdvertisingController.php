<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Advertising;
use Zhiyi\Plus\Models\AdvertisingSpace;

class AdvertisingController extends Controller
{
    /**
     * 获取已安装的广告位信息.
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
        $space->load('advertising');
        $datas = $space->advertising->map(function ($ad) {
            $ad->data = json_decode($ad->data);

            return $ad;
        });

        return response()->json($datas, 200);
    }
}
