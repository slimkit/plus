<?php

namespace Zhiyi\Plus\Exceptions;

use Illuminate\Validation\ValidationException as IlluminateValidationException;

class ValidationException extends IlluminateValidationException
{
    /**
     * Create a new exception instance.
     *
     * @param \Illuminate\Validation\ValidationException $exception
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(IlluminateValidationException $exception)
    {
        parent::__construct($exception->validator, $exception->response, $exception->errorBag);

        $this->status = $exception->status;
        $this->redirectTo = $exception->redirectTo;
        $this->message = trans('validation.message');
    }
}
