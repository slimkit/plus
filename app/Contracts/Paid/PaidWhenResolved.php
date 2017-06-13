<?php

namespace Zhiyi\Plus\Contracts\Paid;

interface PaidWhenResolved
{
    /**
     * Validate the given class instance.
     *
     * @return void
     */
    public function validate();
}
