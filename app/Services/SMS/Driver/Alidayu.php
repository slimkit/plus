<?php

namespace Zhiyi\Plus\Services\SMS\Driver;

use Exception;
use Flc\Alidayu\App as AlidayuApp;
use Zhiyi\Plus\Services\SMS\Message;
use Flc\Alidayu\Client as AlidayuClient;
use Zhiyi\Plus\Services\SMS\DirverInterface;
use Illuminate\Contracts\Foundation\Application;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;

class Alidayu implements DirverInterface
{
    protected $config;
    protected $app;

    /**
     * 构造函数，获取基础依赖.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 设置配置信息.
     *
     * @param array $config
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setConfig(array $config)
    {
        $this->config = [
            'app_key' => array_get($config, 'app_key'),
            'app_secret' => array_get($config, 'app_secret'),
            'sign_name' => array_get($config, 'sign_name'),
            'verify_template_id' => array_get($config, 'verify_template_id'),
        ];

        return $this;
    }

    /**
     * 发送.
     *
     * @param \Zhiyi\Plus\Services\SMS\Message $message
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function send(Message $message)
    {
        $this->registerInstances();
        $response = $this->client()
            ->execute($this->createSendRequest($message));

        $result = isset($response->result) ? $response->result : null;

        if (! is_object($result) || $result->success !== true) {
            throw new Exception('发送阿里大于短信验证码失败');
        }
    }

    /**
     * 创建阿里大于验证码请求类.
     *
     * @param \Zhiyi\Plus\Services\SMS\Message $message
     * @return \Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function createSendRequest(Message $message): AlibabaAliqinFcSmsNumSend
    {
        $request = clone $this->app->make(AlibabaAliqinFcSmsNumSend::class);
        $request->setSmsParam(['code' => array_get($message->getData(), 'code')])
            ->setSmsFreeSignName($this->config['sign_name'])
            ->setSmsTemplateCode($this->config['verify_template_id'])
            ->setRecNum($message->getPhone());

        return $request;
    }

    /**
     * Get SMS client.
     *
     * @return \Flc\Alidayu\Client
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function client(): AlidayuClient
    {
        return $this->app->make(AlidayuClient::class);
    }

    /**
     * 注册共享单例.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function registerInstances()
    {
        $config = $this->config;
        $this->app->singleton(AlidayuApp::class, function () use ($config) {
            return new AlidayuApp($config);
        });

        $app = $this->app->make(AlidayuApp::class);
        $this->app->singleton(AlidayuClient::class, function () use ($app) {
            return new AlidayuClient($app);
        });
    }
}
