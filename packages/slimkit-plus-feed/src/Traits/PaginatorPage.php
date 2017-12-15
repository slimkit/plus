<?php

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
