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

namespace Zhiyi\Plus\AtMessage\Resources;

use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Types\Models as ModelTypes;
use Zhiyi\Plus\AtMessage\ResourceInterface;
use Zhiyi\Plus\Models\Comment as CommentModel;

class Comment implements ResourceInterface
{
    /**
     * The comment resource.
     * @var \Zhiyi\Plus\Models\Comment
     */
    protected $comment;

    /**
     * The sender.
     * @var \Zhiyi\Plus\Models\User
     */
    protected $sender;

    /**
     * Create the resource.
     * @param \Zhiyi\Plus\Models\Comment $comment
     * @param \Zhiyi\Plus\Models\User $sender
     */
    public function __construct(CommentModel $comment, UserModel $sender)
    {
        $this->comment = $comment;
        $this->sender = $sender;
    }

    /**
     * Get the resourceable type.
     * @return string
     */
    public function type(): string
    {
        $alise = ModelTypes::$types[CommentModel::class] ?? null;

        if (is_null($alise)) {
            throw new InvalidArgumentException('不支持的资源');
        }

        return $alise;
    }

    /**
     * Get the resourceable id.
     * @return int
     */
    public function id(): int
    {
        return $this->comment->id;
    }

    /**
     * Get the resourceable push message.
     * @return string
     */
    public function message(): string
    {
        return sprintf('%s在评论中@了你', $this->sender->name);
    }
}
