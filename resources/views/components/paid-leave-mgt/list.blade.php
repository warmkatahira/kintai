<!-- 有給一覧 -->
<div class="scroll_wrap flex flex-grow overflow-scroll">
    <div class="table_parent_div bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-sm block whitespace-nowrap">
            <thead>
                <tr class="text-center text-white bg-gray-600 sticky top-0">
                    <th class="font-thin py-3 px-2">削除</th>
                    <th class="font-thin py-3 px-2">有給取得日</th>
                    <th class="font-thin py-3 px-2">従業員番号</th>
                    <th class="font-thin py-3 px-2">従業員名</th>
                    <th class="font-thin py-3 px-2">拠点</th>
                    <th class="font-thin py-3 px-2">取得日数</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($paidLeaves as $paid_leave)
                    <tr class="text-left hover:bg-theme-sub cursor-default">
                        <td class="py-1 px-2 border text-center">
                            <form method="POST" action="{{ route('paid_leave_mgt.delete') }}" id="paid_leave_delete_form_{{ $paid_leave->paid_leave_id }}" class="m-0">
                                @csrf
                                <input type="hidden" name="paid_leave_id" value="{{ $paid_leave->paid_leave_id }}">
                                <button type="button" id="paid_leave_id_{{ $paid_leave->paid_leave_id }}" class="paid_leave_delete_enter text-center border border-red-500 bg-red-100 text-red-500 text-xs px-2 py-1">削除</button>
                            </form>
                        </td>
                        <td class="py-1 px-2 border text-center">{{ CarbonImmutable::parse($paid_leave->paid_leave_date)->isoFormat('YYYY年MM月DD日(ddd)') }}</td>
                        <td class="py-1 px-2 border text-center">{{ $paid_leave->employee_no }}</td>
                        <td class="py-1 px-2 border">{{ $paid_leave->employee_last_name.' '.$paid_leave->employee_first_name }}</td>
                        <td class="py-1 px-2 border">{{ $paid_leave->base_name }}</td>
                        <td class="py-1 px-2 border text-right">{{ (float)$paid_leave->paid_leave_days }}日</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
