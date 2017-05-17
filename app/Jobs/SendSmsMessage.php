<?php

namespace Zhiyi\Plus\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Zhiyi\Plus\Services\SMS\SMS;
use Zhiyi\Plus\Models\VerifyCode;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendSmsMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $verify;

    /**
     * 任务运行的超时时间。
     *
     * @var int
     */
    public $timeout = 30;

    /**
     * 任务最大尝试次数.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(VerifyCode $verify)
    {
        $this->verify = $verify;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SMS $sms)
    {
        $sms->send($this->verify);

        $this->verify->state = 1;
        $this->verify->save();
    }

    /**
     * 要处理的失败任务.
     *
     * @param Exception $exception
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function failed(Exception $exception)
    {
        $this->verify->state = 2;
        $this->verify->save();

        throw $exception;
    }
}
