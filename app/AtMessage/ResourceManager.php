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

namespace Zhiyi\Plus\AtMessage;

use InvalidArgumentException;
use Zhiyi\Plus\Models\User as UserModel;

class ResourceManager implements ResourceManagerInterface
{
    /**
     * Resource map.
     * @var array
     */
    public static $map = [
        \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed::class => Resources\Feed::class,
        \Zhiyi\Plus\Models\Comment::class => Resources\Comment::class,
    ];

    /**
     * Get resource.
     * @param mixed $resource
     * @param \Zhiyi\Plus\Models\User $sender
     * @return \Zhiyi\Plus\AtMessage\ResourceInterface
     * @throws \InvalidArgumentException
     */
    public function resource($resource, UserModel $sender): ResourceInterface
    {
        $className = $this->getClassName($resource);
        $resourceClass = static::$map[$className] ?? null;
        if (! $resourceClass) {
            throw new InvalidArgumentException(sprintf(
                'Resource [%s] not supported.', $className
            ));
        }

        return new $resourceClass($resource, $sender);
    }

    /**
     * Get resource class name.
     * @param mixed $resource
     * @return string
     */
    public function getClassName($resource): string
    {
        return get_class($resource);
    }
}
