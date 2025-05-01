@vite(['resources/js/role_mgt/role_update.js'])

<x-app-layout>
    <x-page-header content="権限情報更新"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap mb-2">
            <a href="{{ route('role_mgt.index') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
        </div>
        <!-- バリデーションエラー -->
        <x-validation-error-msg />
        <!-- 入力フォーム -->
        <div class="bg-white border border-gray-200 px-10 py-5 whitespace-nowrap">
            <form method="POST" action="{{ route('role_update.update') }}" class="m-0" id="role_update_form">
                @csrf
                <x-create-input label="権限名" type="text" id="role_name" :db="$role->role_name" required="1" tippy="0" />
                <x-role-mgt.create-select label="勤怠管理機能" id="is_kintai_mgt_func_available" :forValue="$roles" :db="$role->is_kintai_mgt_func_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="拠点確認" id="is_base_check_available" :forValue="$roles" :db="$role->is_base_check_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="勤怠操作" id="is_kintai_operation_available" :forValue="$roles" :db="$role->is_kintai_operation_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="コメント操作" id="is_comment_operation_available" :forValue="$roles" :db="$role->is_comment_operation_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="従業員管理機能" id="is_employee_mgt_func_available" :forValue="$roles" :db="$role->is_employee_mgt_func_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="従業員操作" id="is_employee_operation_available" :forValue="$roles" :db="$role->is_employee_operation_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="拠点管理機能" id="is_base_mgt_func_available" :forValue="$roles" :db="$role->is_base_mgt_func_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="手動打刻" id="is_manual_punch_available" :forValue="$roles" :db="$role->is_manual_punch_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="荷主管理機能" id="is_customer_mgt_func_available" :forValue="$roles" :db="$role->is_customer_mgt_func_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="勤怠提出" id="is_kintai_close_available" :forValue="$roles" :db="$role->is_kintai_close_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="ダウンロード機能" id="is_download_func_available" :forValue="$roles" :db="$role->is_download_func_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="勤怠表ダウンロード" id="is_kintai_report_download_available" :forValue="$roles" :db="$role->is_kintai_report_download_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="データダウンロード" id="is_data_download_available" :forValue="$roles" :db="$role->is_data_download_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="その他機能" id="is_other_func_available" :forValue="$roles" :db="$role->is_other_func_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="経理管理機能" id="is_accounting_mgt_func_available" :forValue="$roles" :db="$role->is_accounting_mgt_func_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="システム管理機能" id="is_system_mgt_func_available" :forValue="$roles" :db="$role->is_system_mgt_func_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="ユーザー管理" id="is_user_mgt_available" :forValue="$roles" :db="$role->is_user_mgt_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="権限管理" id="is_role_mgt_available" :forValue="$roles" :db="$role->is_role_mgt_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="休日管理" id="is_holiday_mgt_available" :forValue="$roles" :db="$role->is_holiday_mgt_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="アクセス管理" id="is_access_mgt_available" :forValue="$roles" :db="$role->is_access_mgt_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="拠点管理" id="is_base_mgt_available" :forValue="$roles" :db="$role->is_base_mgt_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="ロック後の勤怠操作" id="is_lock_kintai_operation_available" :forValue="$roles" :db="$role->is_lock_kintai_operation_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="全勤怠操作" id="is_all_kintai_operation_available" :forValue="$roles" :db="$role->is_all_kintai_operation_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="時短情報" id="is_short_time_info_available" :forValue="$roles" :db="$role->is_short_time_info_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="全拠点操作" id="is_all_base_operation_available" :forValue="$roles" :db="$role->is_all_base_operation_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="追加休憩時間表示" id="is_add_rest_time_disp_available" :forValue="$roles" :db="$role->is_add_rest_time_disp_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="派遣会社管理" id="is_temporary_company_mgt_available" :forValue="$roles" :db="$role->is_temporary_company_mgt_available" required="1" tippy="0" />
                <x-role-mgt.create-select label="特別稼働時間表示" id="is_special_woriking_time_disp_available" :forValue="$roles" :db="$role->is_special_woriking_time_disp_available" required="1" tippy="0" />
                <input type="hidden" name="role_id" value="{{ $role->role_id }}">
                <button type="button" id="role_update_enter" class="border border-blue-500 text-blue-500 bg-blue-100 py-1 px-10 mt-5">更新</button>
            </form>
        </div>
    </div>
</x-app-layout>