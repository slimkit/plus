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

namespace Zhiyi\Plus\API2\Requests;

class ListAllComments extends Request
{
    /**
     * The validate rules.
     * @return array
     */
    public function rules(): array
    {
        return [
            'limit' => 'nullable|integer|min:1|max:100',
            'index' => 'nullable|integer|min:0',
            'direction' => 'nullable|string|in:asc,desc',
            'author' => 'nullable|integer|min:0',
            'for_user' => 'nullable|integer|min:0',
            'for_type' => 'nullable|string|in:all,target,reply',
            'id' => 'nullable|string',
            'resourceable_id' => 'nullable|string',
            'resourceable_type' => 'required_with:resourceable_id|string',
        ];
    }

    /**
     * Get the validate error messages.
     * @return array
     */
    public function messages(): array
    {
        return [
            'limit.integer' => 'limit 只能是正整数',
            'limit.min' => '本次请求最少要求获取一条数据',
            'limit.max' => '本次请求最多只能获取 100 条数据',
            'index.integer' => '位置标记只能是正整数',
            'index.min' => '位置标记不能为负数',
            'direction.in' => '数据排序方向只允许 `asc` 或者 `desc`',
            'author.integer' => '作者只允许用户 ID',
            'author.min' => '作者用户 ID 非法',
            'for_user.integer' => '接收用户 ID 非法',
            'for_user.min' => '接收用户 ID 非法',
            'for_type.in' => '接收用户数据类型错误',
        ];
    }
}
