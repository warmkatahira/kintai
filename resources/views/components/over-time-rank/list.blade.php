<!-- 残業ランキング一覧 -->
<div class="scroll_wrap flex flex-grow overflow-scroll">
    <div class="table_parent_div bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-sm block whitespace-nowrap">
            <thead>
                <tr class="text-left text-white bg-gray-600 sticky top-0">
                    <th class="font-thin py-3 px-2 text-center">詳細</th>
                    <th class="font-thin py-3 px-2 text-center">Rank</th>
                    <th class="font-thin py-3 px-2 text-center">年月</th>
                    <th class="font-thin py-3 px-2 text-center">拠点</th>
                    <th class="font-thin py-3 px-2 text-center">従業員区分</th>
                    <th class="font-thin py-3 px-2 text-center">従業員番号</th>
                    <th class="font-thin py-3 px-2 text-center">従業員名</th>
                    <th class="font-thin py-3 px-2 text-center">残業時間</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($employees as $employee)
                    <tr class="text-left hover:bg-theme-sub cursor-default">
                        <td class="py-1 px-2 border text-center">
                            <a href="{{ route('employee_mgt.detail', ['employee_id' => $employee->employee_id]) }}" class="text-center border border-blue-500 bg-blue-100 text-blue-500 text-xs px-2 py-1">詳細</a>
                        </td>
                        <td class="py-1 px-2 border text-center">{{ sprintf('%03d', $employees->firstItem() + $loop->index) }}</td>
                        <td class="py-1 px-2 border text-center">{{ \Carbon\CarbonImmutable::parse($employee->date)->isoFormat('YYYY年MM月') }}</td>
                        <td class="py-1 px-2 border text-center">{{ $employee->base->base_name }}</td>
                        <td class="py-1 px-2 border text-center">{{ $employee->employee_category->employee_category_name }}</td>
                        <td class="py-1 px-2 border text-center">{{ $employee->employee_no }}</td>
                        <td class="py-1 px-2 border text-left">{{ $employee->employee_last_name.' '.$employee->employee_first_name }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($employee->total_over_time / 60, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>