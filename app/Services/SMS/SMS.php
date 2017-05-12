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
    protected $dirver;

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
            ->dispatch(new SendSmsMessage($this->dirver, $verify));
    }

    /**
     * Create SMS send dirver.
     *
     * @throws \RuntimeException
     * @return vodi
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function createDirver()
    {
        $dirverName = $this->app->make('config')->get('sms.default');

        if (! isset(static::$aliases[$dirverName])) {
            throw new RuntimeException(sprintf('This "%s" is not supported by the driver.', $dirverName));
        }

        $config = $this->app->make('config')->get('sms.connections.'.$dirverName, []);

        $this->dirver = $this->app->make(static::$aliases[$dirverName])
            ->setConfig($config);
    }
}
