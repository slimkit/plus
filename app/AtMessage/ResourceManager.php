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
