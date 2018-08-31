<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage;

use Zhiyi\Plus\AppInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Zhiyi\Plus\FileStorage\Channels\ChannelInterface;

class Storage implements StorageInterface
{
    /**
     * The app
     * @var \Zhiyi\Plus\AppInterface
     */
    protected $app;

    /**
     * Channel manager
     * @var
     */
    protected $channelManager;

    /**
     * Create the storage instance.
     * @param \Zhiyi\Plus\AppInterface $app
     * @param \Zhiyi\Plus\FileStorage\ChannelManager $channelManager
     */
    public function __construct(AppInterface $app, ChannelManager $channelManager)
    {
        $this->app = $app;
        $this->channelManager = $channelManager;
    }

    /**
     * Create a upload task.
     * @param \Illuminate\Http\Request $request
     * @return \Zhiyi\Plus\FileStorage\TaskInterface
     */
    public function createTask(Request $request): TaskInterface
    {
        // validate the base rules.
        $this
            ->getCreateTaskValidate()
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

    public function meta(ResourceInterface $resource): FileMetaInterface
    {
        return $this->getChannel($resource)->meta();
    }

    public function url(ResourceInterface $resource, ?string $rule = null): string
    {
        return $this->getChannel($resource)->url($rule);
    }

    /**
     * Deelte a resource.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @return bool
     */
    public function delete(ResourceInterface $resource): ?bool
    {
        return $this->getChannel($resource)->delete();
    }

    /**
     * Transform a resource channel.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @param string $channel
     * @param \Illuminate\Http\Request $request
     * @return \Zhiyi\Plus\FileStorage\ResourceInterface
     */
    public function transform(ResourceInterface $resource, string $channel, Request $request): ResourceInterface
    {
        return $this->getChannel($resource)->transform($this->createResource(
            $channel,
            $this->makePath($resource->getPath())
        ));
    }

    /**
     * Put a file.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @param mixed $content
     * @return bool
     */
    public function put(ResourceInterface $resource, $content): bool
    {
        return $this->getChannel($resource)->put($content);
    }

    /**
     * A storage task callback handle.
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @return void
     */
    public function callback(ResourceInterface $resource): void
    {
        $this->getChannel($resource)->callback();
    }

    public function getChannel(ResourceInterface $resource): ChannelInterface
    {
        $channel = $this->channelManager->driver($resource->getChannel());
        $channel->setResource($resource);

        return $channel;
    }

    public function makePath(string $filename): string
    {
        $path = (new Carbon)->format('Y/m/d');
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        
        return sprintf('%s/%s%s', $path, str_random(64), $ext ? '.'.$ext : '');
    }

    public function getCreateTaskValidate(): Validators\ValidatorInterface
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
