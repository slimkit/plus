<?php
namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers;

use Illuminate\View\View;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;

class HotTopics
{
    public function compose(View $view)
    {
        $params = [
            'limit' => 9
        ];
        $api = '/api/v2/question-topics';

        $topics = api('GET', $api, $params);

        $view->with('topics', $topics);
    }
}