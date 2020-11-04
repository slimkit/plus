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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Admin\Requests;

use Zhiyi\Plus\API2\Requests\Request;

class ListAllTopics extends Request
{
    /**
     * Get the validate rules.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'limit' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:0',
            'name' => 'nullable|string',
            'hot' => 'nullable',
            'id' => 'nullable|integer|min:1',
            'orderBy' => 'nullable|in:id,feeds_count,followers_count',
            'direction' => 'nullable|in:asc,desc',
        ];
    }
}
