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

namespace Zhiyi\Plus\Types;

use Zhiyi\Plus\Models\FeedTopic;

class Models
{
    public const KEY_BY_CLASSNAME = 'classname';
    public const KEY_BY_CLASS_ALIAS = 'class alias';

    public static $types = [
        FeedTopic::class => 'types/models/feed-topics',
    ];

    public function get(?string $keyBy = self::KEY_BY_CLASSNAME, ?string $key = null): ?string
    {
        $types = $this->all($keyBy);

        return $types[$key] ?? null;
    }

    public function all(?string $keyBy = self::KEY_BY_CLASSNAME): array
    {
        if ($keyBy === static::KEY_BY_CLASS_ALIAS) {
            return array_flip(static::$types);
        }

        return static::$types;
    }
}
