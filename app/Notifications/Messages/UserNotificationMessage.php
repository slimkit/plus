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

namespace Zhiyi\Plus\Notifications\Messages;

class UserNotificationMessage
{
    /**
     * The message channel.
     *
     * @var strng
     */
    protected $channel;

    /**
     * The message channel target.
     *
     * @var mixed
     */
    protected $target;

    /**
     * The message content.
     *
     * @var string
     */
    protected $content;

    /**
     * The message extra data.
     *
     * @var array
     */
    protected $extra = [];

    /**
     * The notification subject.
     *
     * @var string
     */
    protected $subject = '';

    /**
     * Create the message instance.
     *
     * @param string $channel
     * @param mixed $target
     * @param string $content
     * @param array $extra
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(string $channel, string $content, array $extra = [])
    {
        $this->channel = $channel;
        $this->content = $content;
        $this->extra = $extra;
    }

    public function setSubject(string $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * The message to array.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function toArray(): array
    {
        return [
            'channel' => $this->channel,
            'content' => $this->content,
            'extra' => $this->extra,
        ];
    }
}
