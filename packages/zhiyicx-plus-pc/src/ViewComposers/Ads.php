<?php
namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;

class Ads
{
    public function compose(View $view)
    {
        $config = Cache::get('config');

        // 获取具体广告位内容
        $ads = api('GET', '/api/v2/advertisingspace/' . $config['ads_space'][$view['space']]['id'] . '/advertising');

        $view->with('ads', $ads);
    }
}