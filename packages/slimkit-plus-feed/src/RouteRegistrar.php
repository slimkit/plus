<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed;

use Illuminate\Contracts\Routing\Registrar as RouterContract;

class RouteRegistrar
{
    /**
     * The router implementation.
     *
     * @var \Illuminate\Contracts\Routing\Registrar
     */
    protected $router;

    /**
     * Create a new route registrar instance.
     *
     * @param  \Illuminate\Contracts\Routing\Registrar  $router
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(RouterContract $router)
    {
        $this->router = $router;
    }

    /**
     * Register all.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function all()
    {
        $this->forAdmin();
        $this->forApi1();
        $this->forApi2();
    }

    /**
     * Register admin routes.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function forAdmin()
    {
        $this->router->group([
            'middleware' => ['web', 'auth', 'admin'],
            'prefix' => '/feed/admin',
            'namespace' => 'Zhiyi\\Component\\ZhiyiPlus\\PlusComponentFeed\\AdminControllers',
        ], dirname(__DIR__).'/routes/admin.php');
    }

    /**
     * Register api 2 routes.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function forApi2()
    {
        $this->router->group([
            'middleware' => ['api'],
            'prefix' => '/api/v2',
            'namespace' => 'Zhiyi\\Component\\ZhiyiPlus\\PlusComponentFeed\\API2',
        ], dirname(__DIR__).'/routes/api2.php');
    }

    /**
     * Register api 1 routes.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function forApi1()
    {
        $this->router->group([
            'middleware' => ['api'],
            'prefix' => '/api/v1',
            'namespace' => 'Zhiyi\\Component\\ZhiyiPlus\\PlusComponentFeed\\Controllers',
        ], dirname(__DIR__).'/routes/api1.php');
    }
}
