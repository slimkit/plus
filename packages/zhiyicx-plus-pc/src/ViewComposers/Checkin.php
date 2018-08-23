<?php
namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers;

use Illuminate\View\View;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;

class CheckIn
{
    public function compose(View $view)
    {
    	$open = app()->make(ConfigRepository::class)->get('checkin.open');

        if ($open) {
    		$data = api('GET', '/api/v2/user/checkin');
	    	$data['checked_in'] = isset($data['checked_in']) && $data['checked_in'] ? 1 : 0;
	        $view->with('data', $data);
   		}
    }
}