<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Models\WalletCash;
use Illuminate\Support\Facades\DB;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Http\Requests\API2\StoreUserWallerCashPost;

class WalletCashController extends Controller
{
    /**
     * 提交提现申请.
     *
     * @param \Zhiyi\Plus\Http\Requests\API2\StoreUserWallerCashPost $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(StoreUserWallerCashPost $request)
    {
        $value = $request->input('value');
        $type = $request->input('type');
        $account = $request->input('account');
        $user = $request->user();

        // Create Cash.
        $cash = new WalletCash();
        $cash->value = $value;
        $cash->type = $type;
        $cash->account = $account;
        $cash->status = 0;

        DB::transaction(function () use ($user, $value, $cash) {
            $user->wallet()->decrement('balance', $value);
            $user->walletCashs()->save($cash);
        });

        return response()
            ->json(['message' => ['提交申请成功']])
            ->setStatusCode(201);
    }
}
