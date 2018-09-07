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

namespace Zhiyi\Plus\API2\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCountsResource extends JsonResource
{
    /**
     * The resource to array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function toArray($request): array
    {
        // unused the $request.
        unset($request);

        return [
            'user' => [
                'following' => $this['user-following'] ?? 0,
                'liked' => $this['user-liked'] ?? 0,
                'commented' => $this['user-commented'] ?? 0,
                'system' => $this['user-system'] ?? 0,
                'news-comment-pinned' => $this['user-news-comment-pinned'] ?? 0,
                'feed-comment-pinned' => $this['user-feed-comment-pinned'] ?? 0,
                'mutual' => $this['user-mutual'] ?? 0,
                'at' => $this['at'] ?? 0,
            ],
        ];
    }
}
