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
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Http\Controllers\Controller;

class WalletLabelController extends Controller
{
    /**
     * Get wallet labels.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function labels()
    {
        $labels = CommonConfig::byNamespace('wallet')
            ->byName('labels')
            ->value('value') ?: '[]';

        if (! $labels) {
            return response()
                ->json([], 200);
        }

        return response()
            ->json()
            ->setJson($labels)
            ->setStatusCode(200);
    }

    /**
     * Create a recharge option tab.
     *
     * @param Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function storeLabel(Request $request)
    {
        $labels = CommonConfig::firstOrNew(
            ['name' => 'labels', 'namespace' => 'wallet'],
            ['value' => '[]']
        );

        $rules = [
            'label' => 'required',
        ];
        $messages = [
            'label.required' => '输入的选项值不能为空',
        ];
        $this->validate($request, $rules, $messages);

        $label = intval($request->input('label'));
        if (count(json_decode($labels->value, true)) === 6) {
            return response()->json(['message' => ['最多只能设置6个选项']], 422);
        }
        if (in_array($label, $_labels = json_decode($labels->value, true))) {
            return response()
                ->json(['messages' => ['选项已经存在，请输入新的选项']])
                ->setStatusCode(422);
        }

        array_push($_labels, $label);

        $labels->value = json_encode($_labels);

        if ($labels->save()) {
            return response()
                ->json(['messages' => ['创建成功']])
                ->setStatusCode(201);
        }

        return response()
            ->json(['messages' => ['创建失败']])
            ->setStatusCode(500);
    }

    /**
     * Remove the recharge option.
     *
     * @param int $label
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function deleteLabel(int $label)
    {
        $labels = CommonConfig::firstOrNew(
            ['name' => 'labels', 'namespace' => 'wallet'],
            ['value' => '[]']
        );

        $items = array_reduce(json_decode($labels->value, true), function (array $labels, $item) use ($label) {
            if (intval($item) !== $label) {
                array_push($labels, $item);
            }

            return $labels;
        }, []);

        $labels->value = json_encode($items);

        if ($labels->save()) {
            return response('', 204);
        }

        return response()
            ->json(['message' => ['删除失败']])
            ->setStatusCode(500);
    }
}
