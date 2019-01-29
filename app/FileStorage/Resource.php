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

namespace Zhiyi\Plus\FileStorage;

class Resource implements ResourceInterface
{
    /**
     * The resource channel name.
     * @var string
     */
    protected $channel;

    /**
     * The resource path.
     * @var string
     */
    protected $path;

    /**
     * The channel and path separate;.
     */
    public const SEPARATE = ':';

    /**
     * Create a resource.
     * @param string $resource E.g "public:/feed/2018/08/HDUi89aHD7Dhnmc8NMud8D90d.png"/"public"
     * @param string|null $path
     */
    public function __construct(string $resource, ?string $path = null)
    {
        if ($resource && $path) {
            $this->channel = $resource;
            $this->path = $path;

            return;
        }

        foreach (ChannelManager::DRIVES as $channelAlias) {
            $channelNameLength = strlen($channelAlias.static::SEPARATE);
            $channel = substr($resource, 0, $channelNameLength);
            if ($channel !== ($channelAlias.static::SEPARATE)) {
                continue;
            }

            return $this->__construct($channelAlias, substr($resource, $channelNameLength));
        }

        throw new Exceptions\ParseResourceException();
    }

    /**
     * Get the resource channel.
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * Get the resource path.
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * The resource to string.
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s:%s', $this->getChannel(), $this->getPath());
    }
}
