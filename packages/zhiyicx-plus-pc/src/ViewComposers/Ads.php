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

        $view->with('ads', $ads);
    }
}
