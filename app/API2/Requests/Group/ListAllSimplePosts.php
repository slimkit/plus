<?php

declare(strict_types=1);

namespace Zhiyi\Plus\API2\Requests\Group;

use Zhiyi\Plus\API2\Requests\Request;

class ListAllSimplePosts extends Request
{
    /**
     * Get the validate rules.
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => 'required'
        ];
    }
}
