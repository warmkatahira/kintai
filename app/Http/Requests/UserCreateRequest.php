<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserCreateRequest extends FormRequest
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
            'user_id' => 'required|max:20|unique:users,user_id',
            'last_name' => 'required|max:10',
            'first_name' => 'required|max:10',
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'required|min:8|max:20|confirmed',
            'password_confirmation' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attributeは必須です。',
            'max' => ":attributeは:max文字以内で入力して下さい。",
            'exists' => ':attributeが存在しません。',
            'unique' => ':attributeは既に使用されています。',
            'min' => ':attributeは:min文字以上で入力して下さい。',
            'email' => ':attributeが正しくありません。',
            'confirmed' => ":attributeが異なります。",
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
            'password' => 'パスワード',
            'password_confirmation' => 'パスワード（確認用）',
        ];
    }
}
