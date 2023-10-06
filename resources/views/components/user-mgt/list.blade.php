<!-- ユーザー一覧 -->
<div class="scroll_wrap flex flex-grow overflow-scroll">
    <div class="table_parent_div bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-sm block whitespace-nowrap">
            <thead>
                <tr class="text-left text-white bg-gray-600 sticky top-0">
                    <th class="font-thin py-3 px-2 text-center">更新</th>
                    <th class="font-thin py-3 px-2 text-center">拠点</th>
                    <th class="font-thin py-3 px-2 text-center">ユーザーID</th>
                    <th class="font-thin py-3 px-2 text-center">ユーザー名</th>
                    <th class="font-thin py-3 px-2 text-center">メールアドレス</th>
                    <th class="font-thin py-3 px-2 text-center">権限</th>
                    <th class="font-thin py-3 px-2 text-center">IPチェックなし</th>
                    <th class="font-thin py-3 px-2 text-center">ステータス</th>
                    <th class="font-thin py-3 px-2 text-center">最終ログイン日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($users as $user)
                    <tr class="text-left hover:bg-theme-sub cursor-default">
                        <td class="py-1 px-2 border text-center">
                            <a href="{{ route('user_update.index', ['id' => $user->id]) }}" class="text-center border border-blue-500 bg-blue-100 text-blue-500 text-xs px-2 py-1">更新</a>
                        </td>
                        <td class="py-1 px-2 border text-left">{{ $user->base->base_name }}</td>
                        <td class="py-1 px-2 border text-left">{{ $user->user_id }}</td>
                        <td class="py-1 px-2 border text-left">{{ $user->last_name.' '.$user->first_name }}</td>
                        <td class="py-1 px-2 border text-left">{{ $user->email }}</td>
                        <td class="py-1 px-2 border text-left">@if(!is_null($user->role)) {{ $user->role->role_name }}@endif</td>
                        <td class="py-1 px-2 border text-center">{{ App\Enums\StatusEnum::get_jp($user->no_ip_check) }}</td>
                        <td class="py-1 px-2 border text-center">{{ App\Enums\StatusEnum::get_jp($user->status) }}</td>
                        <td class="py-1 px-2 border text-center">{{ \Carbon\CarbonImmutable::parse($user->last_login_at)->isoFormat('Y年MM月DD日 HH時mm分ss秒') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>