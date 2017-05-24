<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

// use Illuminate\Http\Request;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Http\Controllers\Controller;

class WalletSettingController extends Controller
{
    /**
     * Get wallet labels.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function labels()
    {
        $labels = CommonConfig::byNamespace('wallet')
            ->byName('labels')
            ->first();

        if (! $labels) {
            return response()
                ->json([], 200);
        }

        return response()
            ->json()
            ->setJson($labels->value);
    }
}
