<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BaseUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'base_id' => 'required|unique:bases,base_id,'.$this->base_id.',base_id',
            'base_name' => 'required|max:20',
            'is_add_rest_available' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attributeは必須です。',
            'max' => ":attributeは:max文字以内で入力して下さい。",
            'boolean' => ':attributeが正しくありません。',
            'unique' => ':attributeは既に使用されています。',
        ];
    }

    public function attributes()
    {
        return [
            'base_id' => '拠点ID',
            'base_name' => '拠点名',
            'is_add_rest_available' => '追加休憩取得有効',
        ];
    }
}
