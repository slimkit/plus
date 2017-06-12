<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Closure;
use Illuminate\Support\Collection;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;

class WalletConfigController extends Controller
{
    /**
     * 格式化数据别名设置.
     *
     * @var array
     */
    protected $aliases = [
        'rule',
        'labels' => ['type' => 'json'],
        'cash' => ['type' => 'json'],
        'wallet:ratio' => ['type' => 'int', 'alias' => 'ratio'],
        'wallet:recharge-type' => ['type' => 'json', 'alias' => 'recharge_type'],
        'cash:min-amount' => ['type' => 'int', 'alias' => 'case_min_amount'],
    ];

    /**
     * 钱包私有配置名称列表.
     *
     * @var array
     */
    protected $walletNames = ['labels', 'rule', 'cash', 'cash:min-amount'];

    /**
     * 钱包共有配置名称列表.
     *
     * @var array
     */
    protected $commonNames = ['wallet:ratio', 'wallet:recharge-type'];

    /**
     * Get wallet info.
     *
     * @param ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(ResponseFactory $response)
    {
        $walletNames = $this->walletNames;
        $commonNames = $this->commonNames;
        $walletOptions = CommonConfig::where(function ($query) use ($walletNames) {
            $query->where('namespace', 'wallet')
                ->whereIn('name', $walletNames);
        })->orWhere(function ($query) use ($commonNames) {
            $query->where('namespace', 'common')
                ->whereIn('name', $commonNames);
        })->get();

        $options = $walletOptions->reduce(
            Closure::bind(function (Collection $options, CommonConfig $item) {
                $this->resolve($options, $item);

                return $options;
            }, $this),
            new Collection()
        );

        return $response
            ->json($options)
            ->setStatusCode(200);
    }

    /**
     * 解决数据结构和别名.
     *
     * @param Collection &$options
     * @param CommonConfig $item
     * @return vodi
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resolve(Collection &$options, CommonConfig $item)
    {
        $name = $item->name;
        $value = $item->value;
        $with = $this->aliases[$name] ?? [];
        $alias = $with['alias'] ?? $name;
        $type = $with['type'] ?? false;
        $options->offsetSet($alias, $this->formatData($type, $value));
    }

    /**
     * 格式化数据.
     *
     * @param string $type
     * @param mixed $value
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function formatData(string $type, $value)
    {
        switch ($type) {
            case 'json':
                return json_decode($value);

            case 'int':
                return intval($value);

            default:
                return $value;
        }
    }
}
