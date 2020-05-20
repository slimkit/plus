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

namespace SlimKit\PlusCheckIn\API\Controllers;

use Exception;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use SlimKit\PlusCheckIn\CacheName\CheckInCacheName;
use SlimKit\PlusCheckIn\Models\CheckinLog as CheckinLogModel;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use Zhiyi\Plus\Packages\Currency\Processes\Common as CommonProcess;
use function Zhiyi\Plus\setting;

class CheckInController extends Controller
{
    /**
     * Check-in attach user balance.
     *
     * @var int
     */
    protected $attach_balance;

    /**
     * Create the controller instance.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct()
    {
        $this->attach_balance = setting('checkin', 'attach-balance', 1);
    }

    /**
     * Get the authenticated user check-in.
     *
     * @param  Request  $request
     * @param  ResponseFactoryContract  $response
     * @param  CheckinLogModel  $model
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(
        Request $request,
        ResponseFactoryContract $response,
        CheckinLogModel $model
    ) {
        $user = $request->user();

        $date = $model->freshTimestamp()->format('Y-m-d');
        $checked_in = (bool) $user->checkinLogs()
            ->whereDate('created_at', $date)
            ->first();
        $users = $model->with('owner')
            ->whereDate('created_at', $date)
            ->orderBy('id', 'asc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return $item->owner;
            });

        return $response->json([
            'rank_users'         => $users,
            'checked_in'         => $checked_in,
            'checkin_count'      => $user->extra->checkin_count ?? 0,
            'last_checkin_count' => $user->extra->last_checkin_count ?? 0,
            'attach_balance'     => $this->attach_balance,
        ])->setStatusCode(200);
    }

    /**
     * Punch the clock.
     *
     * @param  Request  $request
     * @param  ResponseFactoryContract  $response
     * @param  GateContract  $gate
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(
        Request $request,
        ResponseFactoryContract $response,
        GateContract $gate
    ) {
        if (! $this->attach_balance) {
            return $response->json(['message' => trans('plus-checkin::messages.unable')],
                403);
        } elseif ($gate->denies('create', CheckinLogModel::class)) {
            return $response->json(['message' => trans('plus-checkin::messages.checked-in')],
                403);
        }

        $user = $request->user();

        // lasted
        $date = $user->freshTimestamp()->subDay(1)->format('Y-m-d');
        $lasted = (bool) $user->checkinLogs()
            ->whereDate('created_at', $date)
            ->first();

        // Check-in -log.
        $log = new CheckinLogModel();
        $log->amount = $this->attach_balance;

        // user charge.
        $charge = new WalletChargeModel();
        $charge->user_id = $user->id;
        $charge->channel = 'system';
        $charge->action = 1;
        $charge->amount = $log->amount;
        $charge->subject = trans('plus-checkin::messages.charge.subject');
        $charge->body = $charge->subject;
        $charge->status = 1;

        $user->getConnection()->transaction(function () use (
            $user,
            $log,
            $charge,
            $lasted
        ) {

            // Save log
            $user->checkinLogs()->save($log);

            // Save charge and attach balance.
            $user->walletCharges()->save($charge);
            $user->wallet()->increment('balance', $charge->amount);

            // increment check-in count.
            $extra = $user->extra ?: $user->extra()->firstOrCreate([]);
            $extra->checkin_count += 1;
            $extra->last_checkin_count = $lasted ? $extra->last_checkin_count
                + 1 : 1;
            $extra->save();
        });

        return $response->make('', 204);
    }

    /**
     * 签到增加积分.
     *
     * @param  Request  $request
     * @param  ResponseFactoryContract  $response
     * @param  GateContract  $gate
     *
     * @return mixed
     * @throws Exception
     * @author BS <414606094@qq.com>
     */
    public function newStore(
        Request $request,
        ResponseFactoryContract $response,
        GateContract $gate
    ) {
        if (! $this->attach_balance) {
            return $response->json(['message' => trans('plus-checkin::messages.unable')],
                403);
        } elseif ($gate->denies('create', CheckinLogModel::class)) {
            return $response->json(['message' => trans('plus-checkin::messages.checked-in')],
                403);
        }

        $user = $request->user();
        $cacheConfig = config('cache.default');
        $date = $user->freshTimestamp()->subDay(1)->format('Y-m-d');
        // 使用原子锁
        if (in_array($cacheConfig, ['redis', 'memcached'])) {
            Cache::lock(sprintf(CheckInCacheName::CheckLocked,
                    $user->id))
                    ->get(function () use ($user, $date) {
                        $this->checkIn($user, $date);
                    });

            return $response->make('', 204);
        } else {
            // 使用普通锁
            if (Cache::has(sprintf(sprintf(CheckInCacheName::CheckLocked, $user->id)))) {
                return response()->json(null, 429);
            } else {
                $this->checkIn($user, $date);
                Cache::forget(sprintf(sprintf(CheckInCacheName::CheckLocked,
                    $user->id)));

                return $response->make('', 204);
            }
        }
    }

    /**
     * @param  User  $user
     * @param  string  $date
     *
     * @throws \Throwable
     */
    protected function checkIn(User $user, string $date)
    {
        $lasted = Cache::rememberForever(sprintf(CheckInCacheName::CheckInAtDate, $user->id, $date), function () use ($date,
            $user) {
            return $user->checkinLogs()
              ->whereDate('created_at', $date)
              ->exists();
        });

        // Check-in -log.
        $log = new CheckinLogModel();
        $log->amount = $this->attach_balance;

        // user charge.
        $order = new CommonProcess();
        $order = $order->createOrder($user->id, $log->amount, 1,
            trans('plus-checkin::messages.charge.subject'),
            trans('plus-checkin::messages.charge.subject'));

        $user->getConnection()->transaction(function () use (
            $user,
            $log,
            $order,
            $lasted,
            $date
        ) {
            // Save log
            $user->checkinLogs()->save($log);
            // Save charge and attach balance.
            $order->state = 1;
            $order->save();
            $user->currency()
                ->firstOrCreate(['type' => 1], ['sum' => 0])
                ->increment('sum', $order->amount);
            // increment check-in count.
            $extra = $user->extra
                ?: $user->extra()->firstOrCreate([]);
            $extra->checkin_count += 1;
            $extra->last_checkin_count = $lasted
                ? $extra->last_checkin_count
                + 1 : 1;
            $extra->save();
            Cache::forever(sprintf(CheckInCacheName::CheckInAtDate,
                $user->id, $date), true);
        });
    }
}
