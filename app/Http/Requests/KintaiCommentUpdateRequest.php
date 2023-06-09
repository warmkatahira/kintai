<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KintaiCommentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'comment' => 'nullable|max:20',
        ];
    }

    public function messages()
    {
        return [
            'max' => ":attributeは:max文字以内で入力して下さい。",
        ];
    }

    public function attributes()
    {
        return [
            'comment' => 'コメント',
        ];
    }
}
