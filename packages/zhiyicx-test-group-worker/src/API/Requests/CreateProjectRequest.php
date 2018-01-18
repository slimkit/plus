<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\API\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProjectRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'desc' => 'required|string',
            'owner_repo' => 'required|string',
            'branch' => 'required|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function messages()
    {
        return [];
    }
}
