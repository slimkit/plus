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

namespace Zhiyi\Plus\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use function Zhiyi\Plus\findMarkdownImageIDs;
use Zhiyi\Plus\Models\FileWith as FileWithModel;

trait FindMarkdownFileTrait
{
    /**
     * Find markdown image IDs.
     *
     * @param string $markdown
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function findMarkdownImageIDs(string $markdown): array
    {
        return findMarkdownImageIDs($markdown);
    }

    /**
     * Find markdown images of collection.
     *
     * @param string $markdown
     * @param callable $call
     * @return \Illuminate\Support\Collection
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function findMarkdownImageModels(string $markdown, $call = null): Collection
    {
        $IDs = $this->findMarkdownImageIDs($markdown);
        if (empty($IDs)) {
            return new Collection();
        }

        $query = FileWithModel::whereIn('id', $IDs);
        if ($call && is_callable($call)) {
            call_user_func($call, $query);
        }

        return $query->get();
    }

    /**
     * Find markdown images of collection for not with.
     *
     * @param string $markdown
     * @return \Illuminate\Support\Collection
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function findMarkdownImageNotWithModels(string $markdown): Collection
    {
        return $this->findMarkdownImageModels($markdown, function (Builder $query) {
            $query->where('channel', null)
                ->where('raw', null);
        });
    }
}
