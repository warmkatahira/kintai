<x-app-layout>
    <x-page-header content="今月の勤怠詳細"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap mb-2">
            <a href="{{ route('this_month_kintai.index') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
        </div>
        <div class="flex flex-row">
            <p class="w-40 bg-black text-white text-center py-2">従業員名</p>
            <p class="w-96 border border-black py-2 text-center bg-white">{{ $employee->employee_last_name.' '.$employee->employee_first_name }}</p>
        </div>
        <!-- 勤怠表 -->
        <div class="scroll_wrap flex flex-grow overflow-scroll mt-2">
            <div class="table_parent_div bg-white overflow-x-auto overflow-y-auto border border-gray-600">
                <table class="text-xl block whitespace-nowrap">
                    <thead>
                        <tr class="text-center text-white bg-gray-600 border-gray-600 sticky top-0">
                            <th class="font-thin p-2 px-2 w-2/12">出勤日</th>
                            <th class="font-thin p-2 px-2 w-1/12">出勤</th>
                            <th class="font-thin p-2 px-2 w-1/12">退勤</th>
                            <th class="font-thin p-2 px-2 w-1/12">休憩取得</th>
                            <th class="font-thin p-2 px-2 w-1/12">休憩未取得</th>
                            @if(Gate::check('isAddRestTimeDispAvailable') || $add_rest_available)
                                <th class="font-thin p-2 px-2 w-1/12">追加休憩取得</th>
                            @endif
                            <th class="font-thin p-2 px-2 w-1/12">外出</th>
                            <th class="font-thin p-2 px-2 w-1/12">戻り</th>
                            <th class="font-thin p-2 px-2 w-1/12">稼働</th>
                            <th class="font-thin p-2 px-2 w-1/12">残業</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kintais[$employee->employee_id]['kintai'] as $work_day => $value)
                            <tr class="hover:bg-theme-sub text-center">
                                <td class="p-1 px-2 border">{{ \Carbon\CarbonImmutable::parse($work_day)->isoFormat('YYYY年MM月DD日(ddd)') }}</td>
                                <td class="p-1 px-2 border">{{ is_null($value) ? '' : substr($value->begin_time_adj, 0, 5) }}</td>
                                <td class="p-1 px-2 border">{{ is_null($value) ? '' : substr($value->finish_time_adj, 0, 5) }}</td>
                                <td class="p-1 px-2 border">{{ !isset($value->finish_time_adj) ? '' : number_format($value->rest_time / 60, 2) }}</td>
                                <td class="p-1 px-2 border">{{ !isset($value->finish_time_adj) ? '' : number_format($value->no_rest_time / 60, 2) }}</td>
                                @if(Gate::check('isAddRestTimeDispAvailable') || $add_rest_available)
                                    <td class="p-1 px-2 border">{{ is_null($value) ? '' : number_format($value->add_rest_time / 60, 2) }}</td>
                                @endif
                                <td class="p-1 px-2 border">{{ is_null($value) ? '' : substr($value->out_time_adj, 0, 5) }}</td>
                                <td class="p-1 px-2 border">{{ is_null($value) ? '' : substr($value->return_time_adj, 0, 5) }}</td>
                                <td class="p-1 px-2 border">{{ !isset($value->finish_time_adj) ? '' : number_format($value->working_time / 60, 2) }}</td>
                                <td class="p-1 px-2 border {{ !isset($value->finish_time_adj) ? '' : ($value->over_time == 0 ? '' : 'bg-pink-200') }}">{{ !isset($value->finish_time_adj) ? '' : number_format($value->over_time / 60, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
