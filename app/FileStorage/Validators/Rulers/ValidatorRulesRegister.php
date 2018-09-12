<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\FileStorage\Validators\Rulers;

use Zhiyi\Plus\AppInterface;
use Illuminate\Contracts\Validation\Factory as ValidationFactoryContract;

class ValidatorRegister
{
    /**
     * The app.
     * @var \Zhiyi\Plus\AppInterface
     */
    protected $app;

    /**
     * The app validator.
     * @var \Illuminate\Contracts\Validation\Factory
     */
    protected $validator;

    /**
     * The rulers.
     * @var array
     */
    protected $rules = [
        'file_storage',
    ];

    /**
     * Create the validator rules register instance.
     * @param \Zhiyi\Plus\AppInterface $app
     * @param \Illuminate\Contracts\Validation\Factory $validator
     */
    public function __construct(AppInterface $app, ValidationFactoryContract $validator)
    {
        $this->app = $app;
        $this->validator = $validator;
    }

    /**
     * The reguster.
     * @return void
     */
    public function register(): void
    {
        $app = $this->app;
        foreach ($this->rules as $ruleName => $rulerClassname) {
            $this->validator->extend($ruleName, function (...$params) use ($app, $rulerClassname): bool {
                return $app->make($rulerClassname)->handle($params);
            });
        }
    }
}
