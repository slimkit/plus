<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\WalletCharge;
use Illuminate\Database\Eloquent\Builder;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory as ContractResponse;

class WalletChargeController extends Controller
{
    /**
     * 获取凭据列表.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, ContractResponse $response)
    {
        $query = $this->query();
        $this->setUserToBuilder($query, $request);
        $this->setUserNameTobuilder($query, $request);
        $this->setAccountToBuilder($query, $request);
        $this->setWhere($query, $request);

        $query->orderBy('id', 'desc');
        $paginate = $query->paginate(10);
        $paginate->load('user');
        $items = $paginate->items();

        if (empty($items)) {
            return $response
                ->json(['message' => ['没有数据']])
                ->setStatusCode(404);
        }

        return $response->json([
            'total' => $paginate->lastPage(),
            'current' => $paginate->currentPage(),
            'items' => $items,
        ])->setStatusCode(200);
    }

    /**
     * Setting wheres.
     *
     * @param \Illuminate\Database\Eloquent\Builder &$query
     * @param \Illuminate\Http\Request $request
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function setWhere(Builder &$query, Request $request)
    {
        foreach ($request->only(['charge_id', 'transaction_no', 'action', 'status']) as $key => $value) {
            if ($value === null) {
                continue;
            }

            $query->where($key, $value);
        }
    }

    /**
     * Setting account.
     *
     * @param \Illuminate\Database\Eloquent\Builder &$query
     * @param \Illuminate\Http\Request $request
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function setAccountToBuilder(Builder &$query, Request $request)
    {
        $account = $request->query('account');

        if (! $account) {
            return;
        }

        $query->where('account', 'like', '%'.$account.'%');
    }

    /**
     * Setting user id to builder where.
     *
     * @param \Illuminate\Database\Eloquent\Builder &$query
     * @param \Illuminate\Http\Request $request
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function setUserToBuilder(Builder &$query, Request $request)
    {
        $user = intval($request->query('user', 0));

        if ($user === 0) {
            return;
        }

        $query->where('user_id', $user);
    }

    /**
     * Setting user name to builder where.
     *
     * @param Builder &$query
     * @param Request $request
     */
    protected function setUserNameTobuilder(Builder &$query, Request $request)
    {
        $name = $request->query('user_name', null);

        if ($name === null) {
            return;
        }

        $query->whereHas('user', function ($query) use ($name) {
            $query->where('name', 'like', sprintf('%%%s%%', $name));
        });
    }

    /**
     * Get Query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function query(): Builder
    {
        return WalletCharge::query();
    }
}
