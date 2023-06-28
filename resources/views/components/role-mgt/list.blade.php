<!-- 権限一覧 -->
<div class="scroll_wrap flex flex-grow overflow-scroll">
    <div class="table_parent_div bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-sm block whitespace-nowrap">
            <thead>
                <tr class="text-left text-white bg-gray-600 sticky top-0">
                    <th class="font-thin py-3 px-2 text-center">更新</th>
                    <th class="font-thin py-3 px-2 text-center">権限名</th>
                    <th class="font-thin py-3 px-2 text-center">ユーザー数</th>
                    <th class="font-thin py-3 px-2 text-center">勤怠管理機能</th>
                    <th class="font-thin py-3 px-2 text-center">拠点確認</th>
                    <th class="font-thin py-3 px-2 text-center">勤怠操作</th>
                    <th class="font-thin py-3 px-2 text-center">従業員管理機能</th>
                    <th class="font-thin py-3 px-2 text-center">従業員操作</th>
                    <th class="font-thin py-3 px-2 text-center">拠点管理機能</th>
                    <th class="font-thin py-3 px-2 text-center">手動打刻</th>
                    <th class="font-thin py-3 px-2 text-center">荷主管理機能</th>
                    <th class="font-thin py-3 px-2 text-center">勤怠提出</th>
                    <th class="font-thin py-3 px-2 text-center">ダウンロード機能</th>
                    <th class="font-thin py-3 px-2 text-center">勤怠表ダウンロード</th>
                    <th class="font-thin py-3 px-2 text-center">データダウンロード</th>
                    <th class="font-thin py-3 px-2 text-center">その他機能</th>
                    <th class="font-thin py-3 px-2 text-center">経理管理機能</th>
                    <th class="font-thin py-3 px-2 text-center">システム管理機能</th>
                    <th class="font-thin py-3 px-2 text-center">ユーザー管理</th>
                    <th class="font-thin py-3 px-2 text-center">権限管理</th>
                    <th class="font-thin py-3 px-2 text-center">休日管理</th>
                    <th class="font-thin py-3 px-2 text-center">アクセス管理</th>
                    <th class="font-thin py-3 px-2 text-center">拠点管理</th>
                    <th class="font-thin py-3 px-2 text-center">ロック後の勤怠操作</th>
                    <th class="font-thin py-3 px-2 text-center">全勤怠操作</th>
                    <th class="font-thin py-3 px-2 text-center">時短情報</th>
                    <th class="font-thin py-3 px-2 text-center">全拠点操作</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($roles as $role)
                    <tr class="text-left cursor-default">
                        <td class="py-1 px-2 border text-center">
                            <a href="{{ route('role_update.index', ['role_id' => $role->role_id]) }}" class="text-center border border-blue-500 bg-blue-100 text-blue-500 text-xs px-2 py-1">更新</a>
                        </td>
                        <td class="py-1 px-2 border text-center">{{ $role->role_name }}</td>
                        <td class="py-1 px-2 border text-center">{{ $role->users->count() }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_kintai_mgt_func_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_kintai_mgt_func_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_base_check_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_base_check_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_kintai_operation_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_kintai_operation_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_employee_mgt_func_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_employee_mgt_func_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_employee_operation_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_employee_operation_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_base_mgt_func_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_base_mgt_func_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_manual_punch_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_manual_punch_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_customer_mgt_func_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_customer_mgt_func_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_kintai_close_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_kintai_close_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_download_func_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_download_func_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_kintai_report_download_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_kintai_report_download_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_data_download_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_data_download_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_other_func_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_other_func_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_accounting_mgt_func_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_accounting_mgt_func_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_system_mgt_func_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_system_mgt_func_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_user_mgt_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_user_mgt_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_role_mgt_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_role_mgt_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_holiday_mgt_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_holiday_mgt_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_access_mgt_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_access_mgt_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_base_mgt_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_base_mgt_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_lock_kintai_operation_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_lock_kintai_operation_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_all_kintai_operation_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_all_kintai_operation_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_short_time_info_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_short_time_info_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_all_base_operation_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_all_base_operation_available) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>