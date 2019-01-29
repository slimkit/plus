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

namespace Zhiyi\Plus\Http\Middleware;

use Closure;
use Zhiyi\Plus\Models\Sensitive as SensitiveModel;

class DisposeSensitive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$inputs)
    {
        $map = [];
        foreach ($inputs as $input) {
            if ($request->has($input)) {
                $map[$input] = $request->$input;
            }
        }

        $warnings = SensitiveModel::where('type', 'warning')->get();
        foreach ($map as $key => $value) {
            foreach ($warnings as $sensitive) {
                if (strpos((string) $value, $sensitive->word) === false) {
                    continue;
                }

                return response()->json([
                    'message' => '内容包含敏感词',
                    'errors' => [$key => [sprintf('内容包含「%s」为敏感词！', $sensitive->word)]],
                ], 422);
            }
        }

        $replaces = SensitiveModel::where('type', 'replace')
            ->get()
            ->keyBy('word')
            ->map(function ($sensitive) {
                return $sensitive->replace;
            })
            ->toArray();
        $map = array_map(function ($value) use ($replaces) {
            return strtr((string) $value, $replaces);
        }, $map);
        $souceInput = $request->except($inputs);
        $request->replace($map);
        $request->merge($souceInput);

        return $next($request);
    }
}
