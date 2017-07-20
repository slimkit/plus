<?php

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
