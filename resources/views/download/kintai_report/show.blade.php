<x-document-layout>
    {{-- 表紙 --}}
    <div class="page-container p-6">
        <p class="text-3xl p-4 border-b border-black">
            {{ '勤怠表≪'.CarbonImmutable::parse($month)->isoFormat('Y年MM月').'≫' }}
        </p>
        <div class="p-4">
            <table class="border-collapse mb-6">
                <tr>
                    <td class="bg-black text-white text-lg text-center align-middle w-28 border-2 border-black px-4 py-3">拠点</td>
                    <td class="text-2xl align-middle border-2 border-black border-l-0 px-6 py-3 min-w-[220px]">{{ $base['base']->base_name }}</td>
                </tr>
            </table>

            <div class="flex flex-wrap gap-4 mb-8">
                @foreach($base['total_employee'] as $employee_category_name => $total_employee)
                    <div class="border-2 border-black w-40 text-center">
                        <div class="bg-black text-white text-base py-2">{{ $employee_category_name }}</div>
                        <div class="text-3xl py-3">{{ $total_employee }}<span class="text-sm font-normal ml-0.5">人</span></div>
                    </div>
                @endforeach
            </div>

            <div class="">
                <p class="text-xl text-gray-600 mb-8">※集計後、以下を手入力してください</p>
                <div class="w-3/5 flex flex-col gap-10">
                    <div class="flex flex-row justify-between">
                        <p class="text-xl w-32">合計人数</p>
                        <p class="border-b border-black w-52"></p>
                        <p class="text-base pl-2">人</p>
                    </div>
                    <div class="flex flex-row justify-between">
                        <p class="text-xl w-32">合計金額</p>
                        <p class="border-b border-black w-52"></p>
                        <p class="text-base pl-2">円</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 各従業員勤怠表 --}}
    @foreach($kintais as $employee_id => $kintai)
        @php
            // 総祝日稼働時間
            $national_holiday_time = $kintai['national_holiday_total_working_time'];
            // 総特別稼働時間
            $special_time = isset($over40[$employee_id]['total_special_working_time'])
                ? $over40[$employee_id]['total_special_working_time']
                : 0;
            // 総残業時間(通常残業+週40時間超過分+深夜稼働分の合算)
            $total_over_time = ($kintai['total_over_time'] + (!isset($over40[$employee_id]) ? 0 : $over40[$employee_id]['total_over40'])) + $kintai['total_late_night_working_time'];
            // 定時時間以外(残業・祝日稼働・特別稼働のいずれか)が発生しているか判定
            $has_extra_time = $total_over_time > 0 || $national_holiday_time > 0 || $special_time > 0;
        @endphp
        <div class="page-container py-4 px-2">
            <div class="flex flex-row">
                <p class="text-xl mb-2">{{ '勤怠表≪'.CarbonImmutable::parse($month)->isoFormat('Y年MM月').'≫' }}</p>
                @if($has_extra_time)
                    <span class="ml-auto inline-flex items-center gap-1 bg-amber-100 text-amber-800 text-sm px-3 py-1 rounded border border-amber-400">定時時間以外あり</span>
                @endif
            </div>
            <table class="border-separate mb-1" style="border-spacing: 0 4px;">
                <tr>
                    <td class="bg-black text-white text-xs w-32 border border-black px-3">拠点</td>
                    <td class="text-xs border border-black px-3 w-56">{{ $kintai['base_name'] }}</td>
                </tr>
                <tr>
                    <td class="bg-black text-white text-xs w-32 border border-black px-3">従業員番号</td>
                    <td class="text-xs border border-black px-3 w-56">{{ $kintai['employee_no'] }}</td>
                </tr>
                <tr>
                    <td class="bg-black text-white text-xs w-32 border border-black px-3">従業員区分</td>
                    <td class="text-xs border border-black px-3 w-56">{{ $kintai['employee_category_name'] }}</td>
                </tr>
                <tr>
                    <td class="bg-black text-white text-xs w-32 border border-black px-3">従業員名</td>
                    <td class="text-xs border border-black px-3 w-56">{{ $kintai['employee_name'] }}</td>
                </tr>
                <tr>
                    <td class="bg-black text-white text-xs w-32 border border-black px-3">稼働日数</td>
                    <td class="text-xs border border-black px-3 w-56">{{ $kintai['working_days'] }}日</td>
                </tr>
            </table>

            <table class="border-separate mt-2" style="border-spacing: 0 0;">
                <tr>
                    <td class="bg-black text-white text-xs w-32 border border-black px-3">総稼働時間</td>
                    <td class="text-xs border border-black px-3 w-28">{{ number_format($kintai['total_working_time'] / 60, 2) }}時間</td>

                    <td class="bg-black text-white text-xs w-32 border border-black px-3">総祝日稼働時間</td>
                    <td class="text-xs border border-black px-3 w-28">{{ number_format($kintai['national_holiday_total_working_time'] / 60, 2) }}時間</td>

                    <td class="bg-black text-white text-xs w-32 border border-black px-3">総特別稼働時間</td>
                    <td class="text-xs border border-black px-3 w-28">
                        @if(isset($over40[$employee_id]['total_special_working_time']))
                            {{ number_format($over40[$employee_id]['total_special_working_time'] / 60, 2) }}時間
                        @else
                            0.00時間
                        @endif
                    </td>
                </tr>
            </table>

            <table class="border-separate mt-2" style="border-spacing: 0 0;">
                <tr>
                    <td class="bg-black text-white text-xs w-32 border border-black px-3">総残業時間</td>
                    <td class="text-xs border border-black px-3 w-28">
                        {{ number_format((($kintai['total_over_time'] + (!isset($over40[$employee_id]) ? 0 : $over40[$employee_id]['total_over40'])) + $kintai['total_late_night_working_time']) / 60, 2) }}時間
                    </td>

                    <td class="bg-black text-white text-xs w-32 border border-black px-3">通常残業時間</td>
                    <td class="text-xs border border-black px-3 w-28">
                        {{ number_format(((($kintai['total_over_time'] + (!isset($over40[$employee_id]) ? 0 : $over40[$employee_id]['total_over40'])) + $kintai['total_late_night_working_time']) - $kintai['total_late_night_over_time']) / 60, 2) }}時間
                    </td>

                    <td class="bg-black text-white text-xs w-32 border border-black px-3">深夜残業時間</td>
                    <td class="text-xs border border-black px-3 w-28">{{ number_format(($kintai['total_late_night_over_time']) / 60, 2) }}時間</td>
                </tr>
            </table>

            <table class="border-collapse mt-3 w-full text-xs">
                <thead>
                    <tr>
                        <th class="bg-sky-300 border border-black px-0 py-1 font-thin whitespace-nowrap">出勤日</th>
                        <th class="bg-sky-300 border border-black px-1 py-1 font-thin whitespace-nowrap">有給</th>
                        <th class="bg-sky-300 border border-black px-1 py-1 font-thin">出勤</th>
                        <th class="bg-sky-300 border border-black px-1 py-1 font-thin">退勤</th>
                        <th class="bg-sky-300 border border-black px-1 py-1 font-thin">休憩</th>
                        @if($base['base']->is_add_rest_available)
                            <th class="bg-sky-300 border border-black px-1 py-1 font-thin">追休</th>
                        @endif
                        <th class="bg-sky-300 border border-black px-1 py-1 font-thin">外出</th>
                        <th class="bg-sky-300 border border-black px-1 py-1 font-thin">戻り</th>
                        <th class="bg-sky-300 border border-black px-1 py-1 font-thin">稼働</th>
                        <th class="bg-sky-300 border border-black px-1 py-1 font-thin">特別</th>
                        <th class="bg-sky-300 border border-black px-1 py-1 font-thin">残業</th>
                        <th class="bg-sky-300 border border-black px-1 py-1 font-thin">早出</th>
                        <th class="bg-sky-300 border border-black px-1 py-1 font-thin">コメント</th>
                        @if($kintai['employee_category_id'] == App\Enums\EmployeeCategoryEnum::PART_TIME_EMPLOYEE)
                            <th class="bg-sky-300 border border-black px-1 py-1 font-thin">超過</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($kintai['kintai'] as $work_day => $value)
                        <tr style="{{ $date_info[$work_day]['cell_style'] }}">
                            <td class="border border-black px-0 py-0.5 text-center whitespace-nowrap">{{ $date_info[$work_day]['formatted'] }}</td>
                            <td class="border border-black px-1 py-0.5 w-10 text-center">
                                @if(isset($paid_leaves[$employee_id][$work_day]))
                                    {{ $paid_leaves[$employee_id][$work_day] >= 1 ? '○' : '半' }}
                                @endif
                            </td>
                            <td class="border border-black px-1 py-0.5 text-center">{{ is_null($value) ? '' : substr($value->begin_time_adj, 0, 5) }}</td>
                            <td class="border border-black px-1 py-0.5 text-center">{{ is_null($value) ? '' : substr($value->finish_time_adj, 0, 5) }}</td>
                            <td class="border border-black px-1 py-0.5 text-center">{{ !isset($value->finish_time_adj) ? '' : number_format($value->rest_time / 60, 2) }}</td>
                            @if($base['base']->is_add_rest_available)
                                <td class="border border-black px-1 py-0.5 text-center">{{ !isset($value->finish_time_adj) ? '' : number_format($value->add_rest_time / 60, 2) }}</td>
                            @endif
                            <td class="border border-black px-1 py-0.5 text-center">{{ is_null($value) ? '' : substr($value->out_time_adj, 0, 5) }}</td>
                            <td class="border border-black px-1 py-0.5 text-center">{{ is_null($value) ? '' : substr($value->return_time_adj, 0, 5) }}</td>
                            <td class="border border-black px-1 py-0.5 text-center">
                                {{ !isset($value->finish_time_adj) ? '' : number_format($value->working_time / 60, 2) }}{{ !isset($value->finish_time_adj) ? '' : (number_format($value->working_time / 60, 2) <= 6.25 && $kintai['employee_category_id'] == App\Enums\EmployeeCategoryEnum::FULL_TIME_EMPLOYEE ? ' 少' : '') }}
                            </td>
                            <td class="border border-black px-1 py-0.5 text-center">{{ !isset($value->finish_time_adj) ? '' : number_format($value->special_working_time / 60, 2) }}</td>
                            <td class="border border-black px-1 py-0.5 text-center">{{ !isset($value->finish_time_adj) ? '' : number_format(($value->over_time + $value->late_night_working_time) / 60, 2) }}</td>
                            <td class="border border-black px-1 py-0.5 text-center">{{ is_null($value) ? '' : ($value->is_early_worked == 1 ? '○' : '') }}</td>
                            <td class="border border-black px-1 py-0.5 text-left">
                                @php
                                    $is_taiyo = $kintai['base_id'] == '01_1st'
                                        && $kintai['employee_category_id'] == App\Enums\EmployeeCategoryEnum::PART_TIME_EMPLOYEE
                                        && isset($taiyo_working_times[$employee_id][$work_day]);
                                    $comment = is_null($value) ? '' : $value->comment;
                                    $prefix = $is_taiyo ? '【祝日大洋】' : '';
                                    // 崩れない「合計表示幅」の上限。プレフィックス有無で限界が違うので分ける。
                                    //   プレフィックスあり: 15（本体は 15-12=3 相当。テスト済みでOK）
                                    //   プレフィックスなし: 13（本体まるごと。全角が多くても崩れない値）
                                    $max_width = $is_taiyo ? 19 : 17;
                                    $comment_short = mb_strimwidth($comment, 0, max(0, $max_width - mb_strwidth($prefix)), '…');
                                @endphp
                                <div class="whitespace-nowrap" title="{{ $comment }}">
                                    @if($is_taiyo)<span class="font-bold text-red-600">{{ $prefix }}</span>@endif{{ $comment_short }}
                                </div>
                            </td>
                            @if($kintai['employee_category_id'] == App\Enums\EmployeeCategoryEnum::PART_TIME_EMPLOYEE)
                                <td class="border border-black px-1 py-0.5 text-center">
                                    {{ $date_info[$work_day]['is_week_end'] && isset($over40[$employee_id]) ? (isset($over40[$employee_id][$work_day]) ? ($over40[$employee_id][$work_day]->over40 > 0 ? number_format($over40[$employee_id][$work_day]->over40 / 60, 2) : '0.00') : '0.00') : '' }}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- 勤務表下部の計算欄 --}}
            @if($kintai['employee_category_id'] == App\Enums\EmployeeCategoryEnum::PART_TIME_EMPLOYEE)
                {{-- パート：時給単価欄（通常・残業・深夜残業・有給） --}}
                @php
                    // 有給取得日数（取得がなければnull）
                    $paid_leave_days = isset($paid_leaves[$employee_id]) ? array_sum($paid_leaves[$employee_id]) : null;
                @endphp
                @php
                    // 単価の表示形式(円未満がある場合のみ小数第2位まで表示)
                    $format_yen = fn($value) => (floor($value) == $value ? number_format($value) : number_format($value, 2)).'円';
                    // 時給計算(時給単価が未設定の場合はnull)
                    $wage = $wages[$employee_id] ?? null;
                    // 交通費(従業員マスタの支給形態に応じて計算済み)
                    $commuting_allowance = $kintai['commuting_allowance'];
                    // 計算欄の行を作成(unit_price/quantity/amountがnullの場合は手書き用に空欄)
                    $calc_rows = [];
                    foreach (['regular' => '通常', 'over' => '残業', 'late_night_over' => '深夜残業'] as $wage_key => $wage_label) {
                        $calc_rows[] = [
                            'label'      => $wage_label,
                            'unit_price' => is_null($wage) ? null : $format_yen($wage[$wage_key]['unit_price']),
                            'quantity'   => is_null($wage) ? null : number_format($wage[$wage_key]['hours'], 2).'時間',
                            'amount'     => is_null($wage) ? null : $wage[$wage_key]['amount'],
                        ];
                    }
                    // 有給(時給単価×有給時稼働時間×日数)
                    $paid_leave_wage = $wage['paid_leave'] ?? null;
                    $calc_rows[] = [
                        'label'      => is_null($paid_leave_days) ? '' : '有給（'.(float)$paid_leave_days.'日）',
                        'unit_price' => is_null($paid_leave_wage) ? null : $format_yen($paid_leave_wage['unit_price']),
                        'quantity'   => is_null($paid_leave_wage) ? null : number_format($paid_leave_wage['hours'], 2).'時間 × '.$paid_leave_wage['days'].'日',
                        'amount'     => is_null($paid_leave_wage) ? null : $paid_leave_wage['amount'],
                    ];
                    // 交通費
                    $is_monthly_fixed = $commuting_allowance['type'] == App\Enums\CommutingAllowanceEnum::MONTHLY_FIXED;
                    $is_no_commuting = $commuting_allowance['type'] == App\Enums\CommutingAllowanceEnum::NONE;
                    $calc_rows[] = [
                        'label'      => $is_monthly_fixed ? '交通費（月額）' : '交通費',
                        'unit_price' => ($is_no_commuting || $is_monthly_fixed) ? null : $format_yen($commuting_allowance['unit_price']),
                        'quantity'   => ($is_no_commuting || $is_monthly_fixed) ? null : $commuting_allowance['days'].'日',
                        'amount'     => $is_no_commuting ? null : $commuting_allowance['total'],
                        'no_times'   => $is_monthly_fixed,
                    ];
                    // 合計(時給単価が設定されている場合のみ。通常・残業・深夜残業・有給・交通費の合算)
                    $calc_total = is_null($wage) ? null : collect($calc_rows)->sum(fn($row) => $row['amount'] ?? 0);
                @endphp
                <table class="mt-2 text-sm border-collapse">
                    <tbody>
                        @foreach($calc_rows as $row)
                            <tr class="border-black {{ $loop->first ? '' : 'border-t' }} {{ $loop->last ? 'border-b' : '' }}">
                                <td class="py-1 pr-3 w-32 whitespace-nowrap">{{ $row['label'] }}</td>
                                <td class="py-1 px-2 w-28 text-right whitespace-nowrap">{{ $row['unit_price'] }}</td>
                                <td class="py-1 px-2 w-10 text-center">{{ ($row['no_times'] ?? false) ? '' : '×' }}</td>
                                <td class="py-1 px-2 w-48 text-right whitespace-nowrap">{{ $row['quantity'] }}</td>
                                <td class="py-1 px-2 w-10 text-center">=</td>
                                <td class="py-1 pl-2 w-28 text-right whitespace-nowrap">{{ is_null($row['amount']) ? '' : number_format($row['amount']).'円' }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="py-1 pr-3">合計</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="py-1 px-2 text-center">=</td>
                            <td class="py-1 pl-2 text-right whitespace-nowrap font-bold">{{ is_null($calc_total) ? '' : number_format($calc_total).'円' }}</td>
                        </tr>
                    </tbody>
                </table>
            @else
                {{-- パート以外：集計欄 --}}
                @php
                    $summary_items = [
                        ['label' => '出勤日数',   'value' => null, 'unit' => '日'],
                        ['label' => '有給',       'value' => isset($paid_leaves[$employee_id]) ? array_sum($paid_leaves[$employee_id]) : null, 'unit' => '日'],
                        ['label' => '振休',       'value' => null, 'unit' => '日'],
                        ['label' => '欠勤',       'value' => null, 'unit' => '日'],
                        ['label' => '早退',       'value' => null, 'unit' => '回'],
                        ['label' => '遅刻',       'value' => null, 'unit' => '回'],
                        ['label' => '法定内残業', 'value' => null, 'unit' => '時間'],
                        ['label' => '法定外残業', 'value' => null, 'unit' => '時間'],
                        ['label' => '深夜残業',   'value' => null, 'unit' => '時間'],
                        ['label' => '60時間超',   'value' => null, 'unit' => '時間'],
                        ['label' => '超過',   'value' => null, 'unit' => '時間'],
                    ];
                @endphp
                <table class="border-collapse mt-3 w-full text-xs">
                    <tbody>
                        @foreach(array_chunk($summary_items, 3) as $row)
                            <tr>
                                @foreach($row as $item)
                                    <td class="bg-black text-white border border-black px-3 py-1.5 w-28 whitespace-nowrap">{{ $item['label'] }}</td>
                                    <td class="border border-black px-3 py-1.5 text-right">
                                        {{ $item['value'] }}<span class="pl-1">{{ $item['unit'] }}</span>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- 応援稼働がある場合、別ページで出力 --}}
        @if(count($kintai['support_working_time']) != 0)
            <div class="page-container p-6">
                <p class="text-3xl mb-4">応援稼働時間表</p>
                <table class="border-separate mb-1" style="border-spacing: 0 4px;">
                    <tr>
                        <td class="bg-black text-white text-xs w-28 border border-black px-3">年月</td>
                        <td class="text-xs border border-black border-l-0 px-3 w-56">{{ CarbonImmutable::parse($month)->isoFormat('Y年MM月') }}</td>
                    </tr>
                    <tr>
                        <td class="bg-black text-white text-xs w-28 border border-black px-3">拠点</td>
                        <td class="text-xs border border-black border-l-0 px-3 w-56">{{ $kintai['base_name'] }}</td>
                    </tr>
                    <tr>
                        <td class="bg-black text-white text-xs w-28 border border-black px-3">従業員番号</td>
                        <td class="text-xs border border-black border-l-0 px-3 w-56">{{ $kintai['employee_no'] }}</td>
                    </tr>
                    <tr>
                        <td class="bg-black text-white text-xs w-28 border border-black px-3">従業員名</td>
                        <td class="text-xs border border-black border-l-0 px-3 w-56">{{ $kintai['employee_name'] }}</td>
                    </tr>
                </table>
                <table class="border-collapse w-auto text-xs mt-2">
                    <thead>
                        <tr>
                            <th class="bg-sky-300 border border-black px-3 py-1 font-thin whitespace-nowrap">応援先拠点名</th>
                            <th class="bg-sky-300 border border-black px-3 py-1 font-thin whitespace-nowrap">稼働時間</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kintai['support_working_time'] as $support_working_time)
                            <tr>
                                <td class="border border-black px-3 py-0.5 whitespace-nowrap">{{ $support_working_time->base_name }}</td>
                                <td class="border border-black px-3 py-0.5 text-right whitespace-nowrap">{{ number_format($support_working_time->total_customer_working_time / 60, 2) }}時間</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endforeach
</x-document-layout>