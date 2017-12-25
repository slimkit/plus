<?php

namespace SlimKit\PlusQuestion\API2\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnswer extends FormRequest
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
            'body' => 'required_without:anonymity|string',
            'anonymity' => 'required_without:body',
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
            'body' => trans('plus-question::questions.attributes.body'),
            'anonymity' => trans('plus-question::questions.attributes.anonymity'),
        ];
    }
}
