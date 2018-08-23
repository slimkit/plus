<?php
namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers;

use Illuminate\View\View;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;

class HotUsers
{
    public function compose(View $view)
    {
    	$api =  '/api/v2/user/populars';

        $users = api('GET', $api, ['limit' => 5]);
        $view->with('users', $users);
    }
}