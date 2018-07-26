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

use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\Utils\DateTimeToIso8601ZuluString;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TopicCollection extends ResourceCollection
{
    use DateTimeToIso8601ZuluString;

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
            ->map(function ($item) use ($request): array {
                return $this->collectionItemToArray($item, $request);
            })
            ->all();
    }

    /**
     * The collection tem to array.
     *
     * @param \Zhiyi\Plus\Models\FeedTopic $item
     * @return array
     */
    public function collectionItemToArray($item): array
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'logo' => $this->when((bool) $item->logo, $item->logo),
            'created_at' => $this->dateTimeToIso8601ZuluString($item->{Model::CREATED_AT}),
        ];
    }
}
