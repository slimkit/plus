<?php

declare(strict_types=1);

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
        'file_storage'
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
        foreach($this->rules as $ruleName => $rulerClassname) {
            $this->validator->extend($ruleName, function (...$params) use ($app, $rulerClassname): bool {
                return $app->make($rulerClassname)->handle($params);
            });
        }
    }
}
