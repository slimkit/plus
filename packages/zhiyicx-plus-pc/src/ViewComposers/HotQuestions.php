<?php
namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers;

use Illuminate\View\View;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;

class HotQuestions
{
    public function compose(View $view)
    {
        $issues = api('GET', '/api/v2/questions', ['limit' => 9, 'type' => 'hot']);

        $view->with('issues', $issues);
    }
}