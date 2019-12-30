<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\WalletCash;
use Zhiyi\Plus\Models\WalletCharge;

class WalletStatisticsController extends Controller
{
    public function index(Request $request)
    {
        $dateScope = [];

        $startDate = $request->get('start');
        $endDate = $request->get('end');

        if ($startDate && $endDate) {
            $dateScope = $this->generateDateScope($startDate, $endDate);
        } else {
            $dateScope = $this->defaultDateScope();
        }

        $res = [];

        $res[] = $this->chargeStatistics($dateScope);
        $res[] = $this->casheStatistics($dateScope);
        $res[] = $this->lockAmountStatistics($dateScope);

        return response()->json($res, 200);
    }

    /**
     * 充值统计.
     *
     * @param array $scope
     * @return array
     */
    private function chargeStatistics(array $scope)
    {
        $result = WalletCharge::select($this->sql('amount'))
            ->where('status', 1)
            ->where('subject', '余额充值')
            ->whereBetween('created_at', $scope)
            ->first()
            ->toArray();

        $result = array_merge($result, ['type' => '收入（充值）']);

        return $result;
    }

    /**
     * 提现统计.
     *
     * @param array $scope
     * @return array
     */
    private function casheStatistics(array $scope)
    {
        $result = WalletCash::select($this->sql('value'))
            ->where('status', 1)
            ->whereBetween('created_at', $scope)
            ->first()
            ->toArray();

        $result = array_merge($result, ['type' => '提现（支出）']);

        return $result;
    }

    /**
     * 统计锁定的金额.
     *
     * @param  array  $scopre
     * @return array
     */
    public function lockAmountStatistics(array $scope)
    {
        $result = WalletCash::select($this->sql('value'))
            ->where('status', 0)
            ->whereBetween('created_at', $scope)
            ->first()
            ->toArray();

        $result = array_merge($result, ['type' => '锁定金额（提现审核）']);

        return $result;
    }

    /**
     * 统计sql.
     *
     * @param $field
     * @return mixed
     */
    private function sql(string $field)
    {
        return DB::raw(sprintf('count(*) as num, sum(%s) as total_amount', $field));
    }

    /**
     * 默认最近一个月的日期段.
     *
     * @return array
     */
    private function defaultDateScope(): array
    {
        $dateStart = Carbon::now()
            ->addMonth(-1)
            ->startOfDay()
            ->toDateTimeString();

        $dateEnd = Carbon::now()
            ->toDateTimeString();

        return [$dateStart, $dateEnd];
    }

    /**
     * 根据日期生成时间范围.
     *
     * @param $startDate
     * @param $endDate
     * @return array
     */
    private function generateDateScope($startDate, $endDate): array
    {
        $start = Carbon::parse($startDate)->startOfDay()->toDateTimeString();
        $end = Carbon::parse($endDate)->endOfDay()->toDateTimeString();

        return [$start, $end];
    }
}
