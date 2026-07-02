<x-document-layout>
    {{-- 表紙 --}}
    <div class="page-container p-6">
        <p class="text-3xl font-bold p-4 border-b border-black">
            {{ '勤怠表≪'.CarbonImmutable::parse($month)->isoFormat('Y年MM月').'≫' }}
        </p>
        <div class="p-4">
            <table class="border-collapse mb-6">
                <tr>
                    <td class="bg-black text-white font-bold text-lg text-center align-middle w-28 border-2 border-black px-4 py-3">拠点</td>
                    <td class="text-2xl font-bold align-middle border-2 border-black border-l-0 px-6 py-3 min-w-[220px]">{{ $base['base']->base_name }}</td>
                </tr>
            </table>

            <div class="flex flex-wrap gap-4 mb-8">
                @foreach($base['total_employee'] as $employee_category_name => $total_employee)
                    <div class="border-2 border-black w-40 text-center">
                        <div class="bg-black text-white font-bold text-base py-2">{{ $employee_category_name }}</div>
                        <div class="text-3xl font-bold py-3">{{ $total_employee }}<span class="text-sm font-normal ml-0.5">人</span></div>
                    </div>
                @endforeach
            </div>

            <div class="border-t-2 border-black pt-5 max-w-md">
                <p class="text-xs text-gray-600 mb-4">※集計後、以下を手入力してください</p>
                <table class="border-collapse">
                    <tr>
                        <td class="text-base font-bold w-32 pb-5 align-bottom">合計人数</td>
                        <td class="border-b border-black w-44 pb-5 align-bottom"></td>
                        <td class="text-sm pl-2 pb-5 align-bottom">人</td>
                    </tr>
                    <tr>
                        <td class="text-base font-bold w-32 pb-5 align-bottom">合計金額</td>
                        <td class="border-b border-black w-44 pb-5 align-bottom"></td>
                        <td class="text-sm pl-2 pb-5 align-bottom">円</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- 各従業員勤怠表 --}}
    @foreach($kintais as $employee_id => $kintai)
        <div class="page-container p-6">
            <p class="text-3xl mb-2">勤怠表</p>
            <table class="border-separate mb-1" style="border-spacing: 0 4px;">
                <tr>
                    <td class="bg-black text-white text-xs w-32 border border-black px-3">拠点</td>
                    <td class="text-xs border border-black px-3 w-28">{{ $kintai['base_name'] }}</td>
                </tr>
                <tr>
                    <td class="bg-black text-white text-xs w-32 border border-black px-3">従業員番号</td>
                    <td class="text-xs border border-black px-3 w-28">{{ $kintai['employee_no'] }}</td>
                </tr>
                <tr>
                    <td class="bg-black text-white text-xs w-32 border border-black px-3">従業員区分</td>
                    <td class="text-xs border border-black px-3 w-28">{{ $kintai['employee_category_name'] }}</td>
                </tr>
                <tr>
                    <td class="bg-black text-white text-xs w-32 border border-black px-3">従業員名</td>
                    <td class="text-xs border border-black px-3 w-28">{{ $kintai['employee_name'] }}</td>
                </tr>
                <tr>
                    <td class="bg-black text-white text-xs w-32 border border-black px-3">稼働日数</td>
                    <td class="text-xs border border-black px-3 w-28">{{ $kintai['working_days'] }}日</td>
                </tr>
            </table>

            <table class="border-separate mt-2" style="border-spacing: 16px 0;">
                <tr>
                    <td class="bg-black text-white text-xs text-center w-32 border border-black px-3">総稼働時間</td>
                    <td class="text-xs border border-black px-3 w-28">{{ number_format($kintai['total_working_time'] / 60, 2) }}時間</td>

                    <td class="bg-black text-white text-xs text-center w-32 border border-black px-3">総祝日稼働時間</td>
                    <td class="text-xs border border-black px-3 w-28">{{ number_format($kintai['national_holiday_total_working_time'] / 60, 2) }}時間</td>

                    <td class="bg-black text-white text-xs text-center w-32 border border-black px-3">総特別稼働時間</td>
                    <td class="text-xs border border-black px-3 w-28">
                        @if(isset($over40[$employee_id]['total_special_working_time']))
                            {{ number_format($over40[$employee_id]['total_special_working_time'] / 60, 2) }}時間
                        @else
                            0.00時間
                        @endif
                    </td>
                </tr>
            </table>

            <table class="border-separate mt-2" style="border-spacing: 16px 0;">
                <tr>
                    <td class="bg-black text-white text-xs text-center w-32 border border-black px-3">総残業時間</td>
                    <td class="text-xs border border-black px-3 w-28">
                        {{ number_format((($kintai['total_over_time'] + (!isset($over40[$employee_id]) ? 0 : $over40[$employee_id]['total_over40'])) + $kintai['total_late_night_working_time']) / 60, 2) }}時間
                    </td>

                    <td class="bg-black text-white text-xs text-center w-32 border border-black px-3">通常残業時間</td>
                    <td class="text-xs border border-black px-3 w-28">
                        {{ number_format(((($kintai['total_over_time'] + (!isset($over40[$employee_id]) ? 0 : $over40[$employee_id]['total_over40'])) + $kintai['total_late_night_working_time']) - $kintai['total_late_night_over_time']) / 60, 2) }}時間
                    </td>

                    <td class="bg-black text-white text-xs text-center w-32 border border-black px-3">深夜残業時間</td>
                    <td class="text-xs border border-black px-3 w-28">{{ number_format(($kintai['total_late_night_over_time']) / 60, 2) }}時間</td>
                </tr>
            </table>

            <table class="border-collapse mt-3 w-full text-xs">
                <thead>
                    <tr>
                        <th class="bg-sky-300 border border-black px-3 py-1">出勤日</th>
                        <th class="bg-sky-300 border border-black px-3 py-1">出勤</th>
                        <th class="bg-sky-300 border border-black px-3 py-1">退勤</th>
                        <th class="bg-sky-300 border border-black px-3 py-1">休憩</th>
                        @if($base['base']->is_add_rest_available)
                            <th class="bg-sky-300 border border-black px-3 py-1">追休</th>
                        @endif
                        <th class="bg-sky-300 border border-black px-3 py-1">外出</th>
                        <th class="bg-sky-300 border border-black px-3 py-1">戻り</th>
                        <th class="bg-sky-300 border border-black px-3 py-1">稼働</th>
                        <th class="bg-sky-300 border border-black px-3 py-1">特別</th>
                        <th class="bg-sky-300 border border-black px-3 py-1">残業</th>
                        <th class="bg-sky-300 border border-black px-3 py-1">早出</th>
                        <th class="bg-sky-300 border border-black px-3 py-1">コメント</th>
                        @if($kintai['employee_category_id'] == App\Enums\EmployeeCategoryEnum::PART_TIME_EMPLOYEE)
                            <th class="bg-sky-300 border border-black px-3 py-1">超過</th>
                        @endif
                        @if($kintai['base_id'] == '01_1st' && $kintai['employee_category_id'] == App\Enums\EmployeeCategoryEnum::PART_TIME_EMPLOYEE)
                            <th class="bg-sky-300 border border-black px-3 py-1">大洋</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($kintai['kintai'] as $work_day => $value)
                        <tr style="{{ $date_info[$work_day]['cell_style'] }}">
                            <td class="border border-black px-3 py-0.5 text-center">{{ $date_info[$work_day]['formatted'] }}</td>
                            <td class="border border-black px-3 py-0.5 text-center">{{ is_null($value) ? '' : substr($value->begin_time_adj, 0, 5) }}</td>
                            <td class="border border-black px-3 py-0.5 text-center">{{ is_null($value) ? '' : substr($value->finish_time_adj, 0, 5) }}</td>
                            <td class="border border-black px-3 py-0.5 text-center">{{ !isset($value->finish_time_adj) ? '' : number_format($value->rest_time / 60, 2) }}</td>
                            @if($base['base']->is_add_rest_available)
                                <td class="border border-black px-3 py-0.5 text-center">{{ !isset($value->finish_time_adj) ? '' : number_format($value->add_rest_time / 60, 2) }}</td>
                            @endif
                            <td class="border border-black px-3 py-0.5 text-center">{{ is_null($value) ? '' : substr($value->out_time_adj, 0, 5) }}</td>
                            <td class="border border-black px-3 py-0.5 text-center">{{ is_null($value) ? '' : substr($value->return_time_adj, 0, 5) }}</td>
                            <td class="border border-black px-3 py-0.5 text-center">
                                {{ !isset($value->finish_time_adj) ? '' : number_format($value->working_time / 60, 2) }}{{ !isset($value->finish_time_adj) ? '' : (number_format($value->working_time / 60, 2) <= 6.25 && $kintai['employee_category_id'] == App\Enums\EmployeeCategoryEnum::FULL_TIME_EMPLOYEE ? ' 少' : '') }}
                            </td>
                            <td class="border border-black px-3 py-0.5 text-center">{{ !isset($value->finish_time_adj) ? '' : number_format($value->special_working_time / 60, 2) }}</td>
                            <td class="border border-black px-3 py-0.5 text-center">{{ !isset($value->finish_time_adj) ? '' : number_format(($value->over_time + $value->late_night_working_time) / 60, 2) }}</td>
                            <td class="border border-black px-3 py-0.5 text-center">{{ is_null($value) ? '' : ($value->is_early_worked == 1 ? '○' : '') }}</td>
                            <td class="border border-black px-3 py-0.5 text-left">{{ is_null($value) ? '' : $value->comment }}</td>
                            @if($kintai['employee_category_id'] == App\Enums\EmployeeCategoryEnum::PART_TIME_EMPLOYEE)
                                <td class="border border-black px-3 py-0.5 text-center">
                                    {{ $date_info[$work_day]['is_sunday'] && isset($over40[$employee_id]) ? (isset($over40[$employee_id][$work_day]) ? ($over40[$employee_id][$work_day]->over40 > 0 ? number_format($over40[$employee_id][$work_day]->over40 / 60, 2) : '0.00') : '0.00') : '' }}
                                </td>
                            @endif
                            @if($kintai['base_id'] == '01_1st' && $kintai['employee_category_id'] == App\Enums\EmployeeCategoryEnum::PART_TIME_EMPLOYEE)
                                <td class="border border-black px-3 py-0.5 text-center">{{ isset($taiyo_working_times[$employee_id][$work_day]) ? '○' : '' }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- 勤務表下部の計算欄 --}}
            <div class="mt-2">
                <div class="border-b border-black mb-1">
                    <div class="flex flex-wrap gap-x-20 py-1"><span class="text-sm w-24">時給単価</span><span class="text-sm">×</span><span class="text-sm">=</span></div>
                    <div class="flex flex-wrap gap-x-20 py-1 border-t border-black"><span class="text-sm w-24">時給単価</span><span class="text-sm">×</span><span class="text-sm">=</span></div>
                    <div class="flex flex-wrap gap-x-20 py-1 border-t border-black"><span class="text-sm w-24">時給単価</span><span class="text-sm">×</span><span class="text-sm">=</span></div>
                    <div class="flex flex-wrap gap-x-20 py-1 border-t border-black"><span class="text-sm w-24">時給単価</span><span class="text-sm">×</span><span class="text-sm">=</span></div>
                    <div class="flex flex-wrap gap-x-20 py-1 border-t border-black"><span class="text-sm w-24">交通費</span><span class="text-sm">×</span><span class="text-sm">=</span></div>
                </div>
                <div class="flex gap-x-64"><span class="text-sm w-24">合計</span><span class="text-sm">=</span></div>
            </div>
        </div>

        {{-- 応援稼働がある場合、別ページで出力 --}}
        @if(count($kintai['support_working_time']) != 0)
            <div class="page-container p-6">
                <p class="text-3xl font-bold mb-4">応援稼働時間表</p>
                <table class="border-collapse mb-1">
                    <tr>
                        <td class="bg-black text-white font-bold text-xs text-center w-20 border border-black px-3">年月</td>
                        <td class="text-xs font-bold border border-black border-l-0 px-3 w-28">{{ CarbonImmutable::parse($month)->isoFormat('Y年MM月') }}</td>
                    </tr>
                    <tr>
                        <td class="bg-black text-white font-bold text-xs text-center w-20 border border-black px-3">拠点</td>
                        <td class="text-xs font-bold border border-black border-l-0 px-3 w-28">{{ $kintai['base_name'] }}</td>
                    </tr>
                    <tr>
                        <td class="bg-black text-white font-bold text-xs text-center w-20 border border-black px-3">従業員番号</td>
                        <td class="text-xs font-bold border border-black border-l-0 px-3 w-28">{{ $kintai['employee_no'] }}</td>
                    </tr>
                    <tr>
                        <td class="bg-black text-white font-bold text-xs text-center w-20 border border-black px-3">従業員名</td>
                        <td class="text-xs font-bold border border-black border-l-0 px-3 w-28">{{ $kintai['employee_name'] }}</td>
                    </tr>
                </table>
                <table class="border-collapse w-full text-xs mt-2">
                    <thead>
                        <tr>
                            <th class="bg-sky-300 border border-black px-3 py-1">応援先拠点名</th>
                            <th class="bg-sky-300 border border-black px-3 py-1">稼働時間</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kintai['support_working_time'] as $support_working_time)
                            <tr>
                                <td class="border border-black px-3 py-0.5">{{ $support_working_time->base_name }}</td>
                                <td class="border border-black px-3 py-0.5 text-right">{{ number_format($support_working_time->total_customer_working_time / 60, 2) }}時間</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endforeach
</x-document-layout>