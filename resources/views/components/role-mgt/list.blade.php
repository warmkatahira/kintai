<!-- 権限一覧 -->
<div class="scroll_wrap flex flex-grow overflow-scroll">
    <div class="table_parent_div bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-sm block whitespace-nowrap">
            <thead>
                <tr class="text-left text-white bg-gray-600 sticky top-0">
                    <th class="font-thin py-3 px-2 text-center">更新</th>
                    <th class="font-thin py-3 px-2 text-center">権限名</th>
                    <th class="font-thin py-3 px-2 text-center">ユーザー数</th>
                    <th class="font-thin py-3 px-2 text-center">勤怠一覧機能</th>
                    <th class="font-thin py-3 px-2 text-center">従業員一覧機能</th>
                    <th class="font-thin py-3 px-2 text-center">その他機能</th>
                    <th class="font-thin py-3 px-2 text-center">データ出力機能</th>
                    <th class="font-thin py-3 px-2 text-center">管理者機能</th>
                    <th class="font-thin py-3 px-2 text-center">システム管理機能</th>
                    <th class="font-thin py-3 px-2 text-center">経理機能</th>
                    <th class="font-thin py-3 px-2 text-center">勤怠確認</th>
                    <th class="font-thin py-3 px-2 text-center">勤怠削除</th>
                    <th class="font-thin py-3 px-2 text-center">勤怠修正</th>
                    <th class="font-thin py-3 px-2 text-center">全勤怠操作</th>
                    <th class="font-thin py-3 px-2 text-center">従業員操作</th>
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
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_kintai_list_func_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_kintai_list_func_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_employee_list_func_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_employee_list_func_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_other_func_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_other_func_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_data_export_func_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_data_export_func_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_management_func_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_management_func_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_system_mgt_func_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_system_mgt_func_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_accounting_func_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_accounting_func_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_kintai_check_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_kintai_check_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_kintai_delete_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_kintai_delete_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_kintai_modify_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_kintai_modify_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_all_kintai_operation_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_all_kintai_operation_available) }}</td>
                        <td class="py-1 px-2 border text-center {{ App\Enums\RoleEnum::get_class($role->is_employee_operation_available) }}">{{ App\Enums\RoleEnum::get_jp($role->is_employee_operation_available) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>