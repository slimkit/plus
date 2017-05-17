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
        'alidayu' => \Zhiyi\Plus\Services\SMS\Driver\Alidayu::class,
    ];

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 派发发送任务.
     *
     * @param \Zhiyi\Plus\Models\VerifyCode $verify
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function dispatch(VerifyCode $verify)
    {
        $this->app->make(Dispatcher::class)
            ->dispatch(new SendSmsMessage($verify));
    }

    /**
     * 发送验证码.
     *
     * @param \Zhiyi\Plus\Models\VerifyCode $verify
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function send(VerifyCode $verify)
    {
        $message = clone $this->app->make(Message::class);
        $message->setPhone($verify->account)
            ->setMessage(sprintf('验证码%s，如非本人操作，请忽略这条短信。', $verify->code))
            ->setData([
                'code' => $verify->code,
            ]);

        $this->createDirver()
            ->send($message);
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

        $config = (array) $this->app->make('config')->get('sms.connections.'.$driverName, []);

        $driver = $this->app->make(static::$aliases[$driverName]);
        $driver->setConfig($config);

        return $driver;
    }
}
