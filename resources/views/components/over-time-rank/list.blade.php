<!-- 残業ランキング一覧 -->
<div class="scroll_wrap flex flex-grow overflow-scroll">
    <div class="over_time_ranking_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-sm block whitespace-nowrap">
            <thead>
                <tr class="text-left text-white bg-gray-600 sticky top-0">
                    <th class="font-thin py-3 px-2 text-center">Rank</th>
                    <th class="font-thin py-3 px-2 text-center">期間</th>
                    <th class="font-thin py-3 px-2 text-center">拠点</th>
                    <th class="font-thin py-3 px-2 text-center">従業員区分</th>
                    <th class="font-thin py-3 px-2 text-center">従業員番号</th>
                    <th class="font-thin py-3 px-2 text-center">従業員名</th>
                    @if($sameMonthFlg)
                        <th class="font-thin py-3 px-2 text-center">残業時間(当月)</th>
                        <th class="font-thin py-3 px-2 text-center">残業時間(前月)</th>
                        <th class="font-thin py-3 px-2 text-center">前月比</th>
                    @else
                        <th class="font-thin py-3 px-2 text-center">残業時間</th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($employees as $employee)
                    <tr class="text-left hover:bg-theme-sub cursor-default">
                        <td class="py-1 px-2 border text-center">{{ sprintf('%03d', $employees->firstItem() + $loop->index) }}</td>
                        <td class="py-1 px-2 border text-center">{{ CarbonImmutable::parse(session('search_work_day_from'))->isoFormat('YYYY年MM月').'～'.CarbonImmutable::parse(session('search_work_day_to'))->isoFormat('YYYY年MM月') }}</td>
                        <td class="py-1 px-2 border">{{ $employee->base->base_name }}</td>
                        <td class="py-1 px-2 border text-center">{{ $employee->employee_category->employee_category_name }}</td>
                        <td class="py-1 px-2 border text-center">{{ $employee->employee_no }}</td>
                        <td class="py-1 px-2 border text-left">{{ $employee->employee_last_name.' '.$employee->employee_first_name }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($employee->total_over_time / 60, 2) }}</td>
                        @if($sameMonthFlg)
                            <td class="py-1 px-2 border text-right">{{ number_format($employee->pre_total_over_time / 60, 2) }}</td>
                            <td class="py-1 px-2 border text-right">
                                @php
                                    $overtime_difference = ($employee->total_over_time - $employee->pre_total_over_time) / 60;
                                @endphp
                                {{ $overtime_difference > 0 ? '+' . number_format($overtime_difference, 2) : number_format($overtime_difference, 2) }}
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>