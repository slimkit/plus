<?php

namespace Zhiyi\Plus\Services\SMS;

use RuntimeException;
use Zhiyi\Plus\Models\VerifyCode;
use Zhiyi\Plus\Jobs\SendSmsMessage;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Foundation\Application;

class SMS
{
    /**
     * Driver aliases.
     *
     * @var array
     */
    protected static $aliases = [
        'testing' => \Zhiyi\Plus\Services\SMS\Driver\Testing::class,
    ];

    protected $app;
    protected $driver;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->createDirver();
    }

    /**
     * Dispatch a job to its appropriate handler.
     *
     * @param \Zhiyi\Plus\Models\VerifyCode $verify
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function send(VerifyCode $verify)
    {
        $this->app->make(Dispatcher::class)
            ->dispatch(new SendSmsMessage($this->driver, $verify));
    }

    /**
     * Create SMS send driver.
     *
     * @throws \RuntimeException
     * @return vodi
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function createDirver()
    {
        $driverName = $this->app->make('config')->get('sms.default');

        if (! isset(static::$aliases[$driverName])) {
            throw new RuntimeException(sprintf('This "%s" is not supported by the driver.', $driverName));
        }

        $config = $this->app->make('config')->get('sms.connections.'.$driverName, []);

        $this->driver = $this->app->make(static::$aliases[$driverName]);
        $this->driver->setConfig($config);
    }
}
