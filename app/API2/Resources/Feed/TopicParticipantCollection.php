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

namespace Zhiyi\Plus\API2\Resources\Feed;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TopicParticipantCollection extends ResourceCollection
{
    /**
     * The collection to array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return $this
            ->collection
            ->map(function ($item) use ($request) {
                return $this->renderItem($item, $request);
            })
            ->all();
    }

    /**
     * Render the collection item.
     *
     * @param mixed $item
     * @return int
     */
    public function renderItem($item): int
    {
        return $item->user_id;
    }
}
