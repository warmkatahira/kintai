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
            'is_kintai_mgt_func_available' => 'required|boolean',
            'is_base_check_available' => 'required|boolean',
            'is_kintai_operation_available' => 'required|boolean',
            'is_comment_operation_available' => 'required|boolean',
            'is_employee_mgt_func_available' => 'required|boolean',
            'is_employee_operation_available' => 'required|boolean',
            'is_base_mgt_func_available' => 'required|boolean',
            'is_manual_punch_available' => 'required|boolean',
            'is_customer_mgt_func_available' => 'required|boolean',
            'is_kintai_close_available' => 'required|boolean',
            'is_download_func_available' => 'required|boolean',
            'is_kintai_report_download_available' => 'required|boolean',
            'is_data_download_available' => 'required|boolean',
            'is_other_func_available' => 'required|boolean',
            'is_accounting_mgt_func_available' => 'required|boolean',
            'is_system_mgt_func_available' => 'required|boolean',
            'is_user_mgt_available' => 'required|boolean',
            'is_role_mgt_available' => 'required|boolean',
            'is_holiday_mgt_available' => 'required|boolean',
            'is_access_mgt_available' => 'required|boolean',
            'is_base_mgt_available' => 'required|boolean',
            'is_lock_kintai_operation_available' => 'required|boolean',
            'is_all_kintai_operation_available' => 'required|boolean',
            'is_short_time_info_available' => 'required|boolean',
            'is_all_base_operation_available' => 'required|boolean',
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
            'is_kintai_mgt_func_available' => '勤怠管理機能',
            'is_base_check_available' => '拠点確認',
            'is_kintai_operation_available' => '勤怠操作',
            'is_comment_operation_available' => 'コメント操作',
            'is_employee_mgt_func_available' => '従業員管理機能',
            'is_employee_operation_available' => '従業員操作',
            'is_base_mgt_func_available' => '拠点管理機能',
            'is_manual_punch_available' => '手動打刻',
            'is_customer_mgt_func_available' => '荷主管理機能',
            'is_kintai_close_available' => '勤怠提出',
            'is_download_func_available' => 'ダウンロード機能',
            'is_kintai_report_download_available' => '勤怠表ダウンロード',
            'is_data_download_available' => 'データダウンロード',
            'is_other_func_available' => 'その他機能',
            'is_accounting_mgt_func_available' => '経理管理機能',
            'is_system_mgt_func_available' => 'システム管理機能',
            'is_user_mgt_available' => 'ユーザー管理',
            'is_role_mgt_available' => '権限管理',
            'is_holiday_mgt_available' => '休日管理',
            'is_access_mgt_available' => 'アクセス管理',
            'is_base_mgt_available' => '拠点管理',
            'is_lock_kintai_operation_available' => 'ロック後の勤怠操作',
            'is_all_kintai_operation_available' => '全勤怠操作',
            'is_short_time_info_available' => '時短情報',
            'is_all_base_operation_available' => '全拠点操作',
        ];
    }
}
