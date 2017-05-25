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
        })->get();

        $options = $walletOptions->reduce(
            Closure::bind(function (Collection $options, CommonConfig $item) {
                $this->resolveLabel($options, $item);

                return $options;
            }, $this),
            new Collection()
        );

        return $response
            ->json($options)
            ->setStatusCode(200);
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
