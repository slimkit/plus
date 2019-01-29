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

class EditTopic extends Request
{
    /**
     * Get the validator rules.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'logo' => ['nullable', 'string', 'file_storage'],
            'desc' => ['nullable', 'string', 'max:500'],
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
            'desc.max' => '话题描述请控制在 500 字以内',
            'logo.string' => '话题 Logo 数据非法',
            'logo.file_storage' => '话题 Logo 未上传，或使用非法节点',
        ];
    }
}
