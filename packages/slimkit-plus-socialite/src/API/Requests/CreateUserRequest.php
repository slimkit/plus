<?php

namespace SlimKit\PlusSocialite\API\Requests;

class CreateUserRequest extends AccessTokenRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'name' => 'required|string|username|display_length:2,12|unique:users,name',
        ]);
    }
}
