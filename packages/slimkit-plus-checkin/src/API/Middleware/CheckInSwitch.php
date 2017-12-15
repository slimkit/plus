<?php

namespace SlimKit\PlusCheckIn\API\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class CheckInSwitch
{
    /**
     * ThinkSNS+ config repository.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Create the middleware.
     *
     * @param \Illuminate\Contracts\Config\Repository $config
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(ConfigRepository $config)
    {
        $this->config = $config;
    }

    /**
     * The middleware handle.
     *
     * @param mixed $request
     * @param \Closure $next
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle($request, Closure $next)
    {
        if (! $this->config->get('checkin.open')) {
            throw new AuthorizationException(
                trans('plus-checkin::messages.checkin-closed')
            );
        }

        return $next($request);
    }
}
