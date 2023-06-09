<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerGroupCreateRequest extends FormRequest
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
            'customer_group_name' => 'required|max:10',
            'customer_group_sort_order' => 'required|integer|gt:0',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attributeは必須です。',
            'max' => ":attributeは:max文字以内で入力して下さい。",
            'exists' => ':attributeが存在しません。',
            'integer' => ':attributeは整数で設定して下さい。',
            'gt' => ':attributeは0より大きい値で設定して下さい。',
        ];
    }

    public function attributes()
    {
        return [
            'base_id' => '拠点',
            'customer_group_name' => '荷主グループ名',
            'customer_group_sort_order' => '荷主グループ順',
        ];
    }
}
