<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Advertising;
use Zhiyi\Plus\Models\AdvertisingSpace;

class AdvertisingController extends Controller
{
    public function index(Request $request, AdvertisingSpace $space)
    {
        $space = $space->get();

        return response()->json($space, 200);
    }

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
