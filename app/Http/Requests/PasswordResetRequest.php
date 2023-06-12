<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
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
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8|max:20',
            'password_confirmation' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => ":attributeは必須です。",
            'max' => ':attributeは:max文字以内で設定して下さい。',
            'min' => ':attributeは最低:min文字以上で設定して下さい。',
            'email' => ":attributeが正しくありません。",
            'confirmed' => ":attributeが異なります。",
            'token' => 'メールアドレスが一致しません。',
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'メールアドレス',
            'password' => 'パスワード',
            'password_confirmation' => 'パスワード（確認用）',
        ];
    }
}
