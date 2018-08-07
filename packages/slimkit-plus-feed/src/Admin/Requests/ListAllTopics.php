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

namespace Zhiyi\Plus\Packages\Feed\Admin\Requests;

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
