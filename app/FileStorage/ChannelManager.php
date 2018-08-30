<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage;

use Illuminate\Http\Request;
use Zhiyi\Plus\AppInterface;
use InvalidArgumentException;
use Illuminate\Support\Manager;
use function Zhiyi\Plus\setting;
use Zhiyi\Plus\FileStorage\Channels\ChannelInterface;

class ChannelManager extends Manager
{
    public const DRIVES = ['public', 'protected', 'private'];

    protected $fielsystemManager;

    public function __construct(AppInterface $app, FilesystemManager $fielsystemManager)
    {
        parent::__construct($app);
        $this->filesystemManager = $fielsystemManager;
    }

    /**
     * Get the default driver name.
     */
    public function getDefaultDriver()
    {
        return null;
    }

    protected function createPublicDriver(): ChannelInterface
    {
        $filesystem = $this->filesystemManager->driver(
            setting('core', 'file:public-channel-filesystem')
        );

        $channel = $this->app->make(Channels\PublicChannel::class);
        $channel->setFilesystem($filesystem);
        $channel->setRequest($this->app->make(Request::class));

        return $channel;
    }
}
