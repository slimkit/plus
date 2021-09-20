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

namespace Zhiyi\Plus\Packages\Wallet\TargetTypes;

use Zhiyi\Plus\Packages\Wallet\Order;

abstract class Target
{
    /**
     * The order service.
     *
     * @var \Zhiyi\Plus\Packages\Wallet\Order
     */
    protected $order;

    /**
     * Set the order service.
     *
     * @param  \Zhiyi\Plus\Packages\Wallet\Order  $order
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;

        return $this;
    }
}
