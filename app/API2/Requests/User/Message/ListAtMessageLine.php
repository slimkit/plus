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

namespace Zhiyi\Plus\API2\Requests\User\Message;

use Zhiyi\Plus\API2\Requests\Request;

class ListAtMessageLine extends Request
{
    /**
     * Get the validate rules.
     * @return array
     */
    public function rules(): array
    {
        return [
            'limit' => 'nullable|integer|min:1|max:100',
            'index' => 'nullable|integer|min:0',
            'direction' => 'nullable|in:asc,desc',
            'load' => 'nullable|string',
        ];
    }

    /**
     * Get the validate error messages.
     * @return array
     */
    public function messages(): array
    {
        return [
            'limit.integer' => 'limit 参数非法',
            'limit.min' => '最少获取一条数据',
            'limit.max' => '最多获取 100 条数据',
            'index.integer' => 'index 参数非法',
            'inde.min' => 'index 参数不得低于 0',
            'direction.in' => '方向只允许 `asc` 或者 `desc`',
        ];
    }
}
