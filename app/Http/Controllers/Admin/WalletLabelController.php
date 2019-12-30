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

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Plus\setting;

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
        $labels = setting('wallet', 'labels', []);

        return new Response($labels, 200);
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
        $rules = [
            'label' => 'required',
        ];
        $messages = [
            'label.required' => '输入的选项值不能为空',
        ];
        $this->validate($request, $rules, $messages);

        $labels = setting('wallet', 'labels', []);
        $label = intval($request->input('label'));
        if (count($labels) === 6) {
            return response()->json(['message' => ['最多只能设置6个选项']], 422);
        }
        if (in_array($label, $labels)) {
            return response()
                ->json(['messages' => ['选项已经存在，请输入新的选项']])
                ->setStatusCode(422);
        }

        array_push($labels, $label);
        setting('wallet')->set('labels', $labels);

        return response()
            ->json(['messages' => ['创建成功']])
            ->setStatusCode(201);
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
        $labels = setting('wallet', 'labels', []);
        $labels = array_map(function ($item) use ($label) {
            if ($item === $label) {
                return null;
            }

            return $item;
        }, $labels);
        $labels = array_filter($labels);
        setting('wallet')->set('labels', $labels);

        return response('', 204);
    }
}
