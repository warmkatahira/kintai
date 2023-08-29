<!-- 荷主一覧 -->
<div class="scroll_wrap flex flex-grow overflow-scroll">
    <div class="table_parent_div bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-sm block whitespace-nowrap">
            <thead>
                <tr class="text-left text-white bg-gray-600 sticky top-0">
                    <th class="font-thin py-3 px-2 text-center">詳細</th>
                    <th class="font-thin py-3 px-2 text-center">有効/無効</th>
                    <th class="font-thin py-3 px-2 text-center">拠点</th>
                    <th class="font-thin py-3 px-2 text-center">荷主名</th>
                    <th class="font-thin py-3 px-2 text-center">荷主グループ名</th>
                    <th class="font-thin py-3 px-2 text-center">荷主並び順</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($customers as $customer)
                    <tr class="text-left hover:bg-theme-sub cursor-default @if($customer->is_available == 0) text-red-600 @endif">
                        <td class="py-1 px-2 border text-center">
                            <a href="{{ route('customer_mgt.detail', ['customer_id' => $customer->customer_id]) }}" class="text-center border border-blue-500 bg-blue-100 text-blue-500 text-xs px-2 py-1">詳細</a>
                        </td>
                        <td class="py-1 px-2 border text-center">{{ $customer->is_available == 1 ? '有効' : '無効' }}</td>
                        <td class="py-1 px-2 border text-center">{{ $customer->base->base_name }}</td>
                        <td class="py-1 px-2 border text-left">{{ $customer->customer_name }}</td>
                        <td class="py-1 px-2 border text-center">@if(!is_null($customer->customer_group)){{ $customer->customer_group->customer_group_name }}@endif</td>
                        <td class="py-1 px-2 border text-right">{{ $customer->customer_sort_order }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>