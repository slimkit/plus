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
            'logo' => ['nullable', 'string', 'file_storage'],
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
            'logo.string' => '话题 Logo 数据非法',
            'logo.file_storage' => '话题 Logo 未上传，或使用非法节点',
        ];
    }
}
