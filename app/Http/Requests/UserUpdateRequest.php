<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
            'user_id' => 'required|max:20|unique:users,user_id,'.$this->id.',id',
            'last_name' => 'required|max:10',
            'first_name' => 'required|max:10',
            'email' => 'nullable|email|unique:users,email,'.$this->id.',id',
            'role_id' => 'required|exists:roles,role_id',
            'status' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attributeは必須です。',
            'max' => ":attributeは:max文字以内で入力して下さい。",
            'exists' => ':attributeが存在しません。',
            'unique' => ':attributeは既に使用されています。',
            'boolean' => ':attributeが正しくありません。',
            'email' => ':attributeが正しくありません。',
        ];
    }

    public function attributes()
    {
        return [
            'base_id' => '拠点',
            'user_id' => 'ユーザーID',
            'last_name' => 'ユーザー名(姓)',
            'first_name' => 'ユーザー名(名)',
            'email' => 'メールアドレス',
            'role_id' => '権限',
            'status' => 'ステータス',
        ];
    }
}
