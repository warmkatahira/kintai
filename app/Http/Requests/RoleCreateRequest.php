<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleCreateRequest extends FormRequest
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
            'role_name' => 'required|max:10',
            'is_kintai_list_func_available' => 'required|boolean',
            'is_employee_list_func_available' => 'required|boolean',
            'is_other_func_available' => 'required|boolean',
            'is_data_export_func_available' => 'required|boolean',
            'is_management_func_available' => 'required|boolean',
            'is_system_mgt_func_available' => 'required|boolean',
            'is_accounting_func_available' => 'required|boolean',
            'is_kintai_check_available' => 'required|boolean',
            'is_kintai_delete_available' => 'required|boolean',
            'is_kintai_modify_available' => 'required|boolean',
            'is_all_kintai_operation_available' => 'required|boolean',
            'is_employee_operation_available' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attributeは必須です。',
            'max' => ":attributeは:max文字以内で入力して下さい。",
            'boolean' => ':attributeが正しくありません。',
        ];
    }

    public function attributes()
    {
        return [
            'role_name' => '権限名',
            'is_kintai_list_func_available' => '勤怠一覧機能',
            'is_employee_list_func_available' => '従業員一覧機能',
            'is_other_func_available' => 'その他機能',
            'is_data_export_func_available' => 'データ出力機能',
            'is_management_func_available' => '管理者機能',
            'is_system_mgt_func_available' => 'システム管理機能',
            'is_accounting_func_available' => '経理機能',
            'is_kintai_check_available' => '勤怠確認',
            'is_kintai_delete_available' => '勤怠削除',
            'is_kintai_modify_available' => '勤怠修正',
            'is_all_kintai_operation_available' => '全勤怠操作',
            'is_employee_operation_available' => '従業員操作',
        ];
    }
}
