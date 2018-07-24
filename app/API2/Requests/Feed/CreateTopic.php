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

namespace Zhiyi\Plus\API2\Requests\Feed;

use Zhiyi\Plus\API2\Requests\Request;

class CreateTopic extends Request
{
    /**
     * Get the validator rules.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'desc' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * Get the validator error messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => '请输入话题名称',
            'name.max' => '话题名称请控制在 100 字以内',
            'desc.max' => '话题描述请控制在 500 字以内',
            'logo.integer' => '话题 Logo 数据非法',
            'logo.min' => '话题 Logo 文件 ID 非法',
        ];
    }
}
