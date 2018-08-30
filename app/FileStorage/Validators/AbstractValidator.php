<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage\Validators;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class AbstractValidator implements ValidatorInterface
{
    use ValidatesRequests {
        validate as private __validate__;
    }

    /**
     * The Validator validate handle.
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function validate(Request $request): void
    {
        $this->__validate__($request, $this->rules(), $this->messages(), $this->customAttributes());
    }

    /**
     * get The validator rules.
     * @return array
     */
    abstract public function rules(): array;

    /**
     * Get the validate error messages.
     * @return array
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * Get the validate attribute custom name.
     * @return array
     */
    public function customAttributes(): array
    {
        return [];
    }
}
