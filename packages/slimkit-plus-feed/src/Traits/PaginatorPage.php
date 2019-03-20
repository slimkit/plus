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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Traits;

use Illuminate\Contracts\Pagination\Paginator;

trait PaginatorPage
{
    /**
     * 获取下一页页码.
     *
     * @param PaginatorContract $paginator
     * @return int|null|void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getNextPage(Paginator $paginator)
    {
        if ($paginator->hasMorePages()) {
            return $paginator->currentPage() + 1;
        }
    }

    /**
     * 获取上一页的页码.
     *
     * @param PaginatorContract $paginator
     * @return int|null|void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getPrevPage(Paginator $paginator)
    {
        if ($paginator->currentPage() > 1) {
            return $paginator->currentPage() - 1;
        }
    }
}
