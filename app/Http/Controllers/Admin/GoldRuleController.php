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
use Zhiyi\Plus\Models\Ability;
use Zhiyi\Plus\Models\GoldRule;
use Zhiyi\Plus\Http\Controllers\Controller;

class GoldRuleController extends Controller
{
    /**
     * get all rules.
     *
     * @return [type] [description]
     */
    public function rules(Request $request)
    {
        $keyword = $request->get('keyword');

        $items = GoldRule::when($keyword, function ($query) use ($keyword) {
            $query->where('name', 'like', sprintf('%%%s%%', $keyword))
                  ->orWhere('alias', 'like', sprintf('%%%s%%', $keyword));
        })
        ->get();

        return response()->json($items, 200);
    }

    /**
     * show rule.
     *
     * @param  GoldRule $rule
     * @return \Illuminate\Http\JsonResponse
     */
    public function showRule(GoldRule $rule)
    {
        return response()->json($rule, 200);
    }

    /**
     * get not admin abilities.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function abilities()
    {
        $items = Ability::where('name', 'NOT LIKE', sprintf('%%%s%%', 'admin'))
            ->select('name', 'display_name')
            ->get();

        return response()->json($items, 200);
    }

    /**
     * store gold rule.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeRule(Request $request)
    {
        $this->validate($request, $this->rule(), $this->msg());

        $incremental = (int) $request->input('incremental');

        if ($incremental === 0) {
            return response()->json(['message' => ['增量不能为0']], 422);
        }

        GoldRule::create($request->all());

        return response()->json(['message' => ['添加金币规则成功']], 201);
    }

    /**
     * update gold rule.
     *
     * @param Request $request
     * @param GoldRule $goldRule
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRule(Request $request, GoldRule $rule)
    {
        $this->validate($request, $this->rule($rule->id), $this->msg());

        $incremental = (int) $request->input('incremental');

        if ($incremental === 0) {
            return response()->json(['message' => ['增量不能为0']], 422);
        }

        $rule->update($request->all());

        return response()->json(['message' => ['更新金币规则成功']], 201);
    }

    /**
     * delete rule.
     * @param  GoldRule $rule
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRule(GoldRule $rule)
    {
        $rule->delete();

        return response()->json('', 204);
    }

    /**
     * validate rule.
     *
     * @return array
     */
    private function rule($ruleId = null)
    {
        $rules = [
            'name' => 'required',
            'alias' => 'required|unique:gold_rules,alias,'.$ruleId,
            'incremental' => 'required',
            'desc' => 'required',
        ];

        return $rules;
    }

    /**
     * validate rule message.
     *
     * @return array
     */
    private function msg()
    {
        $message = [
          'name.required' => '名称必须填写',
          'alias.required' => '别名必须填写',
          'alias.unique' => '规则已经存在',
          'incremental.required' => '增量必须填写',
          'desc.required' => '描述必须填写',
        ];

        return $message;
    }
}
