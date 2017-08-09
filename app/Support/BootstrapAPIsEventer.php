<?php

namespace Zhiyi\Plus\Support;

use Closure;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcherContract;

class BootstrapAPIsEventer
{
    /**
     * Event displatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * APIs version prefix.
     *
     * @var string
     */
    protected $version_prefix = 'Bootstraping APIs: ';

    /**
     * Create the eventer instance.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(EventsDispatcherContract $events)
    {
        $this->events = $events;
    }

    /**
     * Register an event listener with the dispatcher.
     *
     * @param string $version
     * @param \Closure $callback
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function listen(string $version, Closure $callback)
    {
        $this->events->listen(
            $this->version_prefix.$version,
            $callback
        );
    }

    /**
     * Fire an event and call the listeners.
     *
     * @param string $version
     * @param array $payload
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function dispatch(string $version, array $payload = []): array
    {
        $responses = (array) $this->events->dispatch($this->version_prefix.$version,
            $payload, false
        );

        return array_merge(...$payload, ...array_filter($responses));
    }
}
