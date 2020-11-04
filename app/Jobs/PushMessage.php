<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Zhiyi\Plus\Services\Push;

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
