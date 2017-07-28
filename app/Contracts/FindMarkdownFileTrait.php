<?php

namespace Zhiyi\Plus\Contracts;

use function Zhiyi\Plus\findMarkdownImageIDs;

trait FindMarkdownFileTrait
{
    /**
     * Find markdown image IDs.
     *
     * @param string $markdown
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function findImageIDs(string $markdown): array
    {
        return findMarkdownImageIDs($markdown);
    }
}
