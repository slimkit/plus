<?php
namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers;

use Illuminate\View\View;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;

class IncomeRank
{
    public function compose(View $view)
    {
        $params = [
            'limit' => 5
        ];
        $api = '/api/v2/ranks/income';

        $incomes = api('GET', $api, $params);
        $view->with('incomes', $incomes);
    }
}