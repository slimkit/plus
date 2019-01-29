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
            'logo' => $this->when((bool) $item->logo, function () use ($item) {
                return $item->logo->toArray();
            }),
            'created_at' => $this->dateTimeToIso8601ZuluString($item->{Model::CREATED_AT}),
        ];
    }
}
