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

use Zhiyi\Plus\Models\User as UserModel;

interface ResourceManagerInterface
{
    /**
     * Get resource.
     * @param mixed $resource
     * @param \Zhiyi\Plus\Models\User $sender
     * @return \Zhiyi\Plus\AtMessage\ResourceInterface
     */
    public function resource($resource, UserModel $sender): ResourceInterface;

    /**
     * Get resource class name.
     * @param mixed $resource
     * @return string
     */
    public function getClassName($resource): string;
}
