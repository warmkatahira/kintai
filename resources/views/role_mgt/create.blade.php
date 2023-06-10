@vite(['resources/js/role_mgt/role_create.js'])

<x-app-layout>
    <x-page-header content="権限追加"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap mb-2">
            <a href="{{ route('role_mgt.index') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
        </div>
        <!-- バリデーションエラー -->
        <x-validation-error-msg />
        <!-- 入力フォーム -->
        <div class="bg-white border border-gray-200 px-10 py-5 whitespace-nowrap">
            <form method="POST" action="{{ route('role_create.create') }}" class="m-0" id="role_create_form">
                @csrf
                <x-create-input label="権限名" type="text" id="role_name" :db="null" required="1" tippy="0" />
                <x-role-mgt.create-select label="勤怠一覧機能" id="is_kintai_list_func_available" :forValue="$roles" :db="null" required="1" tippy="0" />
                <x-role-mgt.create-select label="従業員一覧機能" id="is_employee_list_func_available" :forValue="$roles" :db="null" required="1" tippy="0" />
                <x-role-mgt.create-select label="その他機能" id="is_other_func_available" :forValue="$roles" :db="null" required="1" tippy="0" />
                <x-role-mgt.create-select label="データ出力機能" id="is_data_export_func_available" :forValue="$roles" :db="null" required="1" tippy="0" />
                <x-role-mgt.create-select label="管理者機能" id="is_management_func_available" :forValue="$roles" :db="null" required="1" tippy="0" />
                <x-role-mgt.create-select label="システム管理機能" id="is_system_mgt_func_available" :forValue="$roles" :db="null" required="1" tippy="0" />
                <x-role-mgt.create-select label="経理機能" id="is_accounting_func_available" :forValue="$roles" :db="null" required="1" tippy="0" />
                <x-role-mgt.create-select label="勤怠確認" id="is_kintai_check_available" :forValue="$roles" :db="null" required="1" tippy="0" />
                <x-role-mgt.create-select label="勤怠削除" id="is_kintai_delete_available" :forValue="$roles" :db="null" required="1" tippy="0" />
                <x-role-mgt.create-select label="勤怠修正" id="is_kintai_modify_available" :forValue="$roles" :db="null" required="1" tippy="0" />
                <x-role-mgt.create-select label="全勤怠操作" id="is_all_kintai_operation_available" :forValue="$roles" :db="null" required="1" tippy="0" />
                <x-role-mgt.create-select label="従業員操作" id="is_employee_operation_available" :forValue="$roles" :db="null" required="1" tippy="0" />
                <button type="button" id="role_create_enter" class="border border-blue-500 text-blue-500 bg-blue-100 py-1 px-10 mt-5">追加</button>
            </form>
        </div>
    </div>
</x-app-layout>