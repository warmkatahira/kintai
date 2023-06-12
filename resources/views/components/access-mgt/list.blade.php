<!-- IP一覧 -->
<div class="scroll_wrap flex flex-grow overflow-scroll">
    <div class="table_parent_div bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-sm block whitespace-nowrap">
            <thead>
                <tr class="text-left text-white bg-gray-600 sticky top-0">
                    <th class="font-thin py-3 px-2 text-center">更新</th>
                    <th class="font-thin py-3 px-2 text-center">IP</th>
                    <th class="font-thin py-3 px-2 text-center">備考</th>
                    <th class="font-thin py-3 px-2 text-center">有効</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($ipLimits as $ip_limit)
                    <tr class="text-left hover:bg-theme-sub cursor-default">
                        <td class="py-1 px-2 border text-center">
                            <a href="{{ route('ip_limit_update.index', ['ip_limit_id' => $ip_limit->ip_limit_id]) }}" class="text-center border border-blue-500 bg-blue-100 text-blue-500 text-xs px-2 py-1">更新</a>
                        </td>
                        <td class="py-1 px-2 border text-center">{{ $ip_limit->ip }}</td>
                        <td class="py-1 px-2 border text-center">{{ $ip_limit->note }}</td>
                        <td class="py-1 px-2 border text-center">@if($ip_limit->is_available == 1) ○ @endif</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>