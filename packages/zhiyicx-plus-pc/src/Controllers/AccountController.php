<?php

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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;

class AccountController extends BaseController
{
    /**
     * 基本设置.
     * @author Foreach
     * @return mixed
     */
    public function index()
    {
        $this->PlusData['account_cur'] = 'index';
        $user = $this->PlusData['TS'];
        $user['city'] = explode(' ', $user['location']);
        $data['user'] = $user;

        return view('pcview::account.index', $data, $this->PlusData);
    }

    /**
     * 认证
     * @author 28youth
     * @return mixed
     */
    public function authenticate()
    {
        $this->PlusData['account_cur'] = 'authenticate';

        $templet = 'authenticate';
        $data['info'] = api('GET', '/api/v2/user/certification');
        if (isset($data['info']['status'])) {
            $templet = 'authinfo';
        }

        return view('pcview::account.'.$templet, $data, $this->PlusData);
    }

    /**
     * 更新认证
     * @author 28youth
     * @return mixed
     */
    public function updateAuthenticate()
    {
        $this->PlusData['account_cur'] = 'authenticate';
        $data['info'] = api('GET', '/api/v2/user/certification');
        if ($data['info']['status'] == 1) {
            return redirect('/settings/authenticate');
        }

        return view('pcview::account.update_authenticate', $data, $this->PlusData);
    }

    /**
     * 标签管理.
     * @author 28youth
     * @return mixed
     */
    public function tags()
    {
        $this->PlusData['account_cur'] = 'tags';
        $data['tags'] = api('GET', '/api/v2/tags');
        $data['user_tag'] = api('GET', '/api/v2/user/tags');

        return view('pcview::account.tags', $data, $this->PlusData);
    }

    /**
     * 密码修改.
     * @author 28youth
     * @return mixed
     */
    public function security()
    {
        $this->PlusData['account_cur'] = 'security';

        $data['showPassword'] = $this->PlusData['TS']['password'] ? 1 : 0;

        return view('pcview::account.security', $data, $this->PlusData);
    }

    /**
     * 我的钱包.
     * @author Foreach
     * @param  int|int $type    [类型]
     * @return mixed
     */
    public function wallet(int $type = 1)
    {
        $this->PlusData['account_cur'] = 'wallet';

        // $data['order'] = api('GET', '/api/v2/wallet/charges');
        $data['wallet'] = api('GET', '/api/v2/wallet');
        $data['type'] = $type;

        return view('pcview::account.wallet', $data, $this->PlusData);
    }

    /**
     * 钱包记录列表.
     * @author Foreach
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function records(Request $request)
    {
        $type = $request->query('type');

        $params = [
            'after' => $request->query('after') ?: 0,
        ];
        // 交易记录列表
        if ($type == 2) {
            $cate = $request->query('cate');
            if ($cate == 2) {
                $params['action'] = 'income';
            }
            if ($cate == 3) {
                $params['action'] = 'expenses';
            }
            $records = api('GET', '/api/v2/plus-pay/orders', $params);
        }

        // 提现记录列表
        if ($type == 3) {
            $records = api('GET', '/api/v2/plus-pay/cashes', $params);
        }
        $after = last($records)['id'] ?? 0;
        $data['records'] = $records;
        $data['type'] = $type;
        $data['loadcount'] = $request->query('loadcount');

        $html = view('pcview::account.walletrecords', $data, $this->PlusData)->render();

        return response()->json([
            'status'  => true,
            'data' => $html,
            'after' => $after,
        ]);
    }

    /**
     * 充值
     * @author Foreach
     * @return mixed
     */
    public function pay()
    {
        $this->PlusData['account_cur'] = 'wallet';
        $data['wallet'] = api('GET', '/api/v2/wallet');

        return view('pcview::account.walletpay', $data, $this->PlusData);
    }

    /**
     * ping++充值调起.
     * @author Foreach
     * @param  Request $request
     * @return mixed
     */
    public function gateway(Request $request)
    {
        $data['charge'] = $request->query('res');

        return view('pcview::account.gateway', $data, $this->PlusData);
    }

    /**
     * 提现.
     * @author Foreach
     * @return mixed
     */
    public function draw()
    {
        $this->PlusData['account_cur'] = 'wallet';

        return view('pcview::account.walletdraw', $this->PlusData);
    }

    /**
     * 获取绑定信息.
     * @author ZsyD
     * @return mixed
     */
    public function getMyBinds()
    {
        $this->PlusData['account_cur'] = 'binds';

        $data = [
            'phone' => false,
            'email' => false,
            'qq' => false,
            'wechat' => false,
            'weibo' => false,
        ];
        // 手机邮箱绑定状态
        $user = api('GET', '/api/v2/user');

        $data['phone'] = (bool) $user['phone'];
        $data['email'] = (bool) $user['email'];

        // 三方绑定状态
        $binds = api('GET', '/api/v2/user/socialite');
        foreach ($binds as $v) {
            $data[$v] = true;
        }

        return view('pcview::account.binds', $data, $this->PlusData);
    }

    /**
     * 我的积分.
     * @author szlvincent
     * @param  int|int $type    [类型]
     * @return mixed
     */
    public function currency(int $type = 1)
    {
        $this->PlusData['account_cur'] = 'currency';

        $data['currency'] = api('GET', '/api/v2/currency');
        $data['type'] = $type;

        return view('pcview::account.currency', $data, $this->PlusData);
    }

    /**
     * 积分记录列表.
     * @author szlvincent
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function currencyRecords(Request $request)
    {
        $type = $request->query('type');
        $params = [
            'after' => $request->query('after') ?: 0,
            'limit' => 15,
        ];
        switch ($type) {
            case 1:
                // 我的积分
                $currency = api('GET', '/api/v2/currency');
                break;
            case 2:
                // 积分明细
                $currency = api('GET', '/api/v2/currency/orders', $params);
                break;
            case 3:
                //积分充值
                $params['action'] = 'recharge';
                $params['type'] = '1';
                $currency = api('GET', '/api/v2/currency/orders', $params);
                break;
            case 4:
                //积分提现
                $params['action'] = 'cash';
                $params['type'] = '-1';
                $currency = api('GET', '/api/v2/currency/orders', $params);
                break;
        }
        $after = 0;
        if ($type != 1) {
            $data['loadcount'] = $request->query('loadcount');
            $after = last($currency)['id'] ?? 0;
        }
        $data['currency'] = $currency;
        $data['type'] = $type;
        $html = view('pcview::account.currencyrecords', $data, $this->PlusData)->render();

        return response()->json([
            'status'  => true,
            'data' => $html,
            'after' => $after,
        ]);
    }

    /**
     * 积分充值
     * @author szlvincent
     * @return mixed
     */
    public function currencyPay()
    {
        $this->PlusData['account_cur'] = 'currency';
        $data['currency'] = api('GET', '/api/v2/currency');
        $data['currency']['recharge-options'] = explode(',', $data['currency']['recharge-options']);

        return view('pcview::account.currencypay', $data, $this->PlusData);
    }

    /**
     * 积分提取.
     * @author szlvincent
     * @return mixed
     */
    public function currencyDraw()
    {
        $this->PlusData['account_cur'] = 'currency';
        $data['currency'] = api('GET', '/api/v2/currency');

        return view('pcview::account.currencydraw', $data, $this->PlusData);
    }
}
