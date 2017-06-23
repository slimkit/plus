<?php

namespace Zhiyi\Plus\Contracts\Model;

interface FetchComment
{
    /**
     * Get comment centent.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getCommentContentAttribute(): string;

    /**
     * Get target source display title.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getTargetTitleAttribute(): string;

    /**
     * Get target source image file with ID.
     *
     * @return int
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getTargetImageAttribute(): int;

    /**
     * Get target source id.
     *
     * @return int
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getTargetIdAttribute(): int;
}
