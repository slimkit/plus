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

class Models
{
    /**
     * Key by classname const.
     * @var string
     */
    public const KEY_BY_CLASSNAME = 'classname';

    /**
     * Key by class alise const,.
     * @var string
     */
    public const KEY_BY_CLASS_ALIAS = 'class alias';

    /**
     * Types.
     * @var array
     */
    public static $types = [
        \Zhiyi\Plus\Models\FeedTopic::class => 'types/models/feed-topics',
        \Zhiyi\Plus\Models\User::class => 'users', /* 旧关系别名，保持不变 */
        \Zhiyi\Plus\Models\Comment::class => 'comments', /* 旧关系别名，保持不变 */
        \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed::class => 'feeds', /* 旧关系别名，保持不变 */
        \Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music::class => 'musics', /* 旧关系别名，保持不变 */
        \Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial::class => 'music_specials', /* 旧关系别名，保持不变 */
        \Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News::class => 'news', /* 旧关系别名，保持不变 */
    ];

    /**
     * Get classname alise or get alias classname.
     *
     * @param string $keyBy
     * @param string $key
     * @return string|null
     */
    public function get(string $key = null, ?string $keyBy = self::KEY_BY_CLASSNAME): ?string
    {
        $types = $this->all($keyBy);

        return $types[$key] ?? null;
    }

    /**
     * Get all types.
     *
     * @param string $keyBy
     * @return array
     */
    public function all(?string $keyBy = self::KEY_BY_CLASSNAME): array
    {
        if ($keyBy === static::KEY_BY_CLASS_ALIAS) {
            return array_flip(static::$types);
        }

        return static::$types;
    }
}
