<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V1;

use Carbon\Carbon;
use Zhiyi\Plus\Models\Area;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;

class AreaController extends Controller
{
    /**
     * 获取全部地区数据.
     *
     * @param Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function showAreas(Request $request)
    {
        $ids = $this->str2array($request->query('id'));
        $pids = $this->str2array($request->query('pid'));

        $query = app(Area::class)->newQuery();

        if (! empty($ids)) {
            $query->whereIn('id', $ids);
        }

        if (! empty($pids)) {
            $query->whereIn('pid', $pids);
        }

        $cacheKey = 'areas-id'.implode('-', $ids).'-pid'.implode('-', $pids);
        $expiresAt = Carbon::now()->addMonth(12);

        $areas = app('cache')->remember($cacheKey, $expiresAt, function () use ($query) {
            return $query->get();
        });

        return response()->json(static::createJsonData([
            'status' => true,
            'data' => $areas,
        ]))->setStatusCode(200);
    }

    /**
     * 字符串转换为数组.
     *
     * @param array|string $source
     * @param string $delimiter
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    private function str2array($source, $delimiter = ','): array
    {
        $array = $source;
        if (! is_array($source)) {
            $array = explode($delimiter, $source);
        }

        $append = false;
        array_map(function ($value) use (&$append) {
            if ($value === '0') {
                $append = 0;
            }
        }, $array);

        $array = array_filter($array);

        if ($append !== false) {
            array_push($array, $append);
        }

        return $array;
    }
}
