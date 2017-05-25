<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Closure;
use Illuminate\Support\Collection;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;

class WalletController extends Controller
{
    /**
     * Get wallet info.
     *
     * @param ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(ResponseFactory $response)
    {
        $walletOptions = CommonConfig::where(function ($query) {
            $query->where('namespace', 'wallet')
                ->whereIn('name', ['labels']);
        })->orWhere(function ($query) {
            $query->where('namespace', 'common')
                ->whereIn('name', ['wallet:ratio']);
        })->get();

        $options = $walletOptions->reduce(
            Closure::bind(function (Collection $options, CommonConfig $item) {
                $this->resolveLabel($options, $item);
                $this->resolveRatio($options, $item);

                return $options;
            }, $this),
            new Collection()
        );

        // 预设结构.
        $options->offsetSet('rule', '我是积分规则纯文本.');
        $options->offsetSet('alipay', [
            'open' => false,
        ]);
        $options->offsetSet('apple', [
            'open' => false,
        ]);
        $options->offsetSet('wechat', [
            'open' => false,
        ]);
        $options->offsetSet('cash', [
            'types' => ['alipay'],
        ]);

        return $response
            ->json($options)
            ->setStatusCode(200);
    }

    /**
     * Resolve ratio.
     *
     * @param Collection &$options
     * @param CommonConfig $item
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resolveRatio(Collection &$options, CommonConfig $item)
    {
        if ($item->name === 'wallet:ratio') {
            $options->offsetSet('ratio', intval($item->value));
        }
    }

    /**
     * Resolve label.
     *
     * @param Collection &$options
     * @param CommonConfig $item
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resolveLabel(Collection &$options, CommonConfig $item)
    {
        if ($item->name === 'labels') {
            $options->offsetSet('labels', json_decode($item->value));
        }
    }
}
