<?php

namespace SlimKit\PlusCheckIn\API\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use SlimKit\PlusCheckIn\Models\CheckinLog as CheckinLogModel;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

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
    public function __construct(ConfigRepository $config)
    {
        $this->attach_balance = $config->get('checkin.attach_balance');
    }

    /**
     * Get the authenticated user check-in.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusCheckIn\Models\CheckinLog $model
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, ResponseFactoryContract $response, CheckinLogModel $model)
    {
        $user = $request->user();

        $date = $model->freshTimestamp()->format('Y-m-d');
        $checked_in = (bool) $user->checkinLogs()
            ->whereDate('created_at', $date)
            ->first();
        $users = $model->with('onwer')
            ->whereDate('created_at', $date)
            ->orderBy('id', 'asc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return $item->onwer;
            });

        return $response->json([
            'rank_users' => $users,
            'checked_in' => $checked_in,
            'checkin_count' => $user->extra->checkin_count ?? 0,
            'last_checkin_count' => $user->extra->last_checkin_count ?? 0,
            'attach_balance' => $this->attach_balance,
        ])->setStatusCode(200);
    }

    /**
     * Punch the clock.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Illuminate\Contracts\Auth\Access\Gate $gate
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(Request $request, ResponseFactoryContract $response, GateContract $gate)
    {
        if (! $this->attach_balance) {
            return $response->json(['message' => trans('plus-checkin::messages.unable')], 403);
        } elseif ($gate->denies('create', CheckinLogModel::class)) {
            return $response->json(['message' => trans('plus-checkin::messages.checked-in')], 403);
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

        $user->getConnection()->transaction(function () use ($user, $log, $charge, $lasted) {

            // Save log
            $user->checkinLogs()->save($log);

            // Save charge and attach balance.
            $user->walletCharges()->save($charge);
            $user->wallet()->increment('balance', $charge->amount);

            // increment check-in count.
            $extra = $user->extra ?: $user->extra()->firstOrCreate([]);
            $extra->checkin_count += 1;
            $extra->last_checkin_count = $lasted ? $extra->last_checkin_count + 1 : 1;
            $extra->save();
        });

        return $response->make('', 204);
    }
}
