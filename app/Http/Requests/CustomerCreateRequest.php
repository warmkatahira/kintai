<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerCreateRequest extends FormRequest
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
            'base_id' => 'required|exists:bases,base_id',
            'customer_name' => 'required|max:20',
            'customer_group_id' => 'nullable|exists:customer_groups,customer_group_id',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attributeは必須です。',
            'max' => ":attributeは:max文字以内で入力して下さい。",
            'exists' => ':attributeが存在しません。',
        ];
    }

    public function attributes()
    {
        return [
            'base_id' => '拠点',
            'customer_name' => '荷主名',
            'customer_group_id' => '荷主グループ',
        ];
    }
}
