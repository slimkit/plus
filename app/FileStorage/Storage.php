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

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Zhiyi\Plus\AppInterface;
use Zhiyi\Plus\FileStorage\Channels\ChannelInterface;
use Zhiyi\Plus\FileStorage\Validators\ValidatorInterface;

class Storage implements StorageInterface
{
    /**
     * The app.
     *
     * @var AppInterface
     */
    protected $app;
    /**
     * Channel manager.
     *
     * @var
     */
    protected $channelManager;

    /**
     * Create the storage instance.
     *
     * @param  AppInterface  $app
     * @param  ChannelManager  $channelManager
     */
    public function __construct(
        AppInterface $app,
        ChannelManager $channelManager
    ) {
        $this->app = $app;
        $this->channelManager = $channelManager;
    }

    /**
     * Create a upload task.
     *
     * @param  Request  $request
     *
     * @return TaskInterface
     */
    public function createTask(Request $request): TaskInterface
    {
        // validate the base rules.
        $this
            ->getCreateTaskValidator()
            ->validate($request);

        // Create resource
        $resource = $this->createResource(
            $request->input('storage.channel'),
            $this->makePath($request->input('filename'))
        );

        // Create task
        $channel = $this->getChannel($resource);
        $channel->setRequest($request);

        return $channel->createTask();
    }

    /**
     * Get a file info.
     *
     * @param  ResourceInterface  $resource
     *
     * @return \Zhiyi\Plus\FileMetaInterface
     */
    public function meta(ResourceInterface $resource): FileMetaInterface
    {
        return $this->getChannel($resource)->meta();
    }

    /**
     * Get a file response.
     *
     * @param  ResourceInterface  $resource
     * @param  string|null  $rule
     *
     * @return string
     */
    public function response(ResourceInterface $resource, ?string $rule = null): Response
    {
        return $this->getChannel($resource)->response($rule);
    }

    /**
     * Deelte a resource.
     *
     * @param  ResourceInterface  $resource
     *
     * @return bool
     */
    public function delete(ResourceInterface $resource): ?bool
    {
        return $this->getChannel($resource)->delete();
    }

    /**
     * Put a file.
     *
     * @param  ResourceInterface  $resource
     * @param  mixed  $content
     *
     * @return bool
     */
    public function put(ResourceInterface $resource, $content): bool
    {
        return $this->getChannel($resource)->put($content);
    }

    /**
     * A storage task callback handle.
     *
     * @param  ResourceInterface  $resource
     *
     * @return void
     */
    public function callback(ResourceInterface $resource): void
    {
        $this->getChannel($resource)->callback();
    }

    /**
     * Get a channel instance.
     *
     * @param  ResourceInterface  $resource
     *
     * @return ChannelInterface
     */
    public function getChannel(ResourceInterface $resource): ChannelInterface
    {
        $channel = $this->channelManager->driver($resource->getChannel());
        $channel->setResource($resource);

        return $channel;
    }

    /**
     * Make a new path.
     *
     * @param  string  $filename
     *
     * @return string
     */
    public function makePath(string $filename): string
    {
        $path = (new Carbon)->format('Y/m/d');
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        return sprintf('%s/%s%s', $path, Str::random(64), $ext ? '.'.$ext : '');
    }

    /**
     * Get create task validator.
     *
     * @return ValidatorInterface
     */
    public function getCreateTaskValidator(): Validators\ValidatorInterface
    {
        return $this->app->make(
            Validators\CreateTaskValidator::class
        );
    }

    public function createResource(...$params): ResourceInterface
    {
        return new Resource(...$params);
    }
}
