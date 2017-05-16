<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;

class BootstrappersController extends Controller
{
    /**
     * 获取启动者配置列表.
     *
     * @param ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(ResponseFactory $response)
    {
        $bootstrappers = [];
        foreach (CommonConfig::byNamespace('common')->get() as $bootstrapper) {
            $bootstrappers[$bootstrapper->name] = $this->formatValue($bootstrapper->value);
        }

        return $response->json($bootstrappers)->setStatusCode(200);
    }

    /**
     * 格式化数据.
     *
     * @param string $value
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function formatValue(string $value)
    {
        if (($data = json_decode($value, true)) === null) {
            return $value;
        }

        return $data;
    }
}
