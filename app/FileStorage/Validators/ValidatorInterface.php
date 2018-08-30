<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage\Validators;

use Illuminate\Http\Request;

interface ValidatorInterface
{
    /**
     * The validate
     */
    public function validate(Request $request): void;
}
