<?php

namespace Zhiyi\Plus\Http\Requests\API2;

use Illuminate\Foundation\Http\FormRequest;

class StoreUploadFile extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required|max:2048|file|mimes:jpeg,bmp,png,gif,txt'
        ];
    }
}
