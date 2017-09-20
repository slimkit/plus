<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\WalletCash;
use Zhiyi\Plus\Models\WalletCharge;
use Zhiyi\Plus\Http\Controllers\Controller;

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
        return DB::raw(sprintf('count(*) as num, sum(%s/100) as total_amount', $field));
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
        $model = new User();

        return [$start, $end];
    }
}
