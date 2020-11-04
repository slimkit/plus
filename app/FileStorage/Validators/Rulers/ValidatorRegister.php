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

namespace Zhiyi\Plus\FileStorage\Validators\Rulers;

use Illuminate\Contracts\Validation\Factory as ValidationFactoryContract;
use Zhiyi\Plus\AppInterface;

class ValidatorRegister
{
    /**
     * The app.
     *
     * @var AppInterface
     */
    protected $app;
    /**
     * The app validator.
     *
     * @var ValidationFactoryContract
     */
    protected $validator;
    /**
     * The rulers.
     *
     * @var array
     */
    protected $rules
        = [
            'file_storage' => FileStorageRuler::class,
        ];

    /**
     * Create the validator rules register instance.
     *
     * @param  AppInterface  $app
     * @param  ValidationFactoryContract  $validator
     */
    public function __construct(
        AppInterface $app,
        ValidationFactoryContract $validator
    ) {
        $this->app = $app;
        $this->validator = $validator;
    }

    /**
     * The reguster.
     *
     * @return void
     */
    public function register(): void
    {
        $app = $this->app;
        foreach ($this->rules as $ruleName => $rulerClassname) {
            $this->validator->extend($ruleName,
                function (...$params) use ($app, $rulerClassname): bool {
                    return $app->make($rulerClassname)->handle($params);
                });
        }
    }
}
