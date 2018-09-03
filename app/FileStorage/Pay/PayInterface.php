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
