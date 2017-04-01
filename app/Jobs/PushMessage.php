<?php

namespace Zhiyi\Plus\Jobs;

use Illuminate\Bus\Queueable;
use Zhiyi\Plus\Services\Push;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PushMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // 提示信息
    protected $alert;

    // 别名
    protected $audience;

    // 参数  （api 约定参数有  type - 应用名 action - 动作  例如 ['type' => 'feed', 'action' => 'comment']
    protected $extras;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($alert, $audience, $extras = [])
    {
        $this->alert = $alert;
        $this->audience = $audience;
        $this->extras = $extras;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Push $push)
    {
        $push->push($this->alert, $this->audience, $this->extras);
    }
}
