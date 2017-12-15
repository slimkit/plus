<?php

namespace SlimKit\PlusCheckIn\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConfig extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function rules(): array
    {
        return [
            // 'switch' => 'required',
            'balance' => 'required|integer|min:1',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function attributes(): array
    {
        return [
            'switch' => trans('plus-checkin::admin.switch'),
            'balance' => trans('plus-checkin::admin.balance'),
        ];
    }
}
