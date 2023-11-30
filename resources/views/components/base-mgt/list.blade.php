<!-- 拠点一覧 -->
<div class="scroll_wrap flex flex-grow overflow-scroll">
    <div class="table_parent_div bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-sm block whitespace-nowrap">
            <thead>
                <tr class="text-left text-white bg-gray-600 sticky top-0">
                    <th class="font-thin py-3 px-2 text-center">詳細</th>
                    <th class="font-thin py-3 px-2 text-center">拠点ID</th>
                    <th class="font-thin py-3 px-2 text-center">拠点名</th>
                    <th class="font-thin py-3 px-2 text-center">追加休憩取得</th>
                    <th class="font-thin py-3 px-2 text-center">休憩関連選択モード</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($bases as $base)
                    <tr class="text-left hover:bg-theme-sub cursor-default">
                        <td class="py-1 px-2 border text-center">
                            <a href="{{ route('base_update.index', ['base_id' => $base->base_id]) }}" class="text-center border border-blue-500 bg-blue-100 text-blue-500 text-xs px-2 py-1">更新</a>
                        </td>
                        <td class="py-1 px-2 border text-left">{{ $base->base_id }}</td>
                        <td class="py-1 px-2 border text-left">{{ $base->base_name }}</td>
                        <td class="py-1 px-2 border text-center">@if($base->is_add_rest_available == 1) ○ @endif</td>
                        <td class="py-1 px-2 border text-center">{{ $base->rest_related_select_mode == 'no_rest' ? '休憩未取得' : '休憩取得' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>