<?php

namespace SlimKit\PlusQuestion\Admin\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Auth\JWTAuthToken;
use Zhiyi\Plus\Support\Configuration;

class HomeController extends Controller
{
    public function index(Request $request, JWTAuthToken $jwt)
    {
        config('jwt.single_auth', false);
        return view('plus-question::admin', [
            'api_token' => $jwt->create($request->user())
        ]);
    }

    /**
     * Get Question & answer switch.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function switch()
    {
        return response()->json([
            'switch' => config('question.app.switch'),
            'apply_amount' => config('question.apply_amount'),
            'onlookers_amount' => config('question.onlookers_amount'),
            'anonymity_rule' => config('question.anonymity_rule'),
        ]);
    }

    /**
     * Store Question & Answer switch.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Support\Configuration $config
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(Request $request, Configuration $config)
    {
        $switch = (bool) $request->input('switch');
        $apply_amount = (int) $request->input('apply_amount');
        $onlookers_amount = (int) $request->input('onlookers_amount');
        $anonymity_rule = (string) $request->input('anonymity_rule');

        $config->set([
            'question.app.switch' => $switch,
            'question.apply_amount' => $apply_amount,
            'question.onlookers_amount' => $onlookers_amount,
            'question.anonymity_rule' => $anonymity_rule,
        ]);

        return response()->json(['message' => '设置成功'], 201);
    }
}
