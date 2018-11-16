<?php
namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers;

use Illuminate\View\View;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;

class QuestionRank
{
    public function compose(View $view)
    {
    	$qrank['day'] = api('GET', '/api/v2/question-ranks/answers', ['limit' => 5, 'type' => 'day']);
    	$qrank['week'] = api('GET', '/api/v2/question-ranks/answers', ['limit' => 5, 'type' => 'week']);
    	$qrank['month'] = api('GET', '/api/v2/question-ranks/answers', ['limit' => 5, 'type' => 'month']);

        $view->with('qrank', $qrank);
    }
}