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

        $bootstrappers['ad'] = [
            [
                'id' => 1,
                'title' => '广告1',
                'type' => 'image', // image, markdown, html, feed:id, user:id,
                'data' => [
                    'image' => 'https://avatars0.githubusercontent.com/u/5564821?v=3&s=460',
                    'link' => 'https://github.com/zhiyicx/thinksns-plus',
                ],
            ],
            [
                'id' => 2,
                'title' => '广告2',
                'type' => 'markdown',
                'data' => '# 广告2'.PHP_EOL.'我是广告2',
            ],
            [
                'id' => 3,
                'title' => '广告3',
                'type' => 'html',
                'data' => '<h1>广告3</h1><p>我不管我不管</p><script>alert(\'我是广告3\')</script>',
            ],
            [
                'id' => 4,
                'title' => '广告4',
                'type' => 'user:id',
                'data' => '1',
            ],
        ];

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
