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

namespace Zhiyi\Plus\FileStorage\Pay;

interface PayInterface
{
    /**
     * Get paid status.
     * @return bool
     */
    public function getPaid(): bool;

    /**
     * Get file paid node amount.
     * @return int
     */
    public function getAmount(): int;

    /**
     * Get pay node.
     * @return string
     */
    public function getNode(): string;

    /**
     * Get pay type.
     * @return string
     */
    public function getType(): string;
}
