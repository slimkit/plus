<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Zhiyi\Plus\Http\Controllers\Controller;

class CdnController extends Controller
{
    /**
     * Get selected cdn.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getCdnSelected()
    {
        return response()->json(['seleced' => config('cdn.default')], 200);
    }
}
