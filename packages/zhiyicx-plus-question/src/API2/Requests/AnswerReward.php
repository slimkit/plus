<?php

namespace SlimKit\PlusQuestion\API2\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnswerReward extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function rules(): array
    {
        $balance = $this->user()->wallet->balance ?? 0;

        return [
            'amount' => ['required', 'integer', 'min:1', 'max:'.$balance],
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
        return [
            'amount.required' => trans('plus-question::answers.reward.required'),
            'amount.min' => trans('plus-question::answers.reward.min'),
            'amount.max' => trans('plus-question::answers.reward.max'),
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
            'amount' => trans('plus-question::answers.reward.attributes.amount'),
        ];
    }
}
