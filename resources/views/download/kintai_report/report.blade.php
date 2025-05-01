<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css">
            @font-face {
                font-family: ipaexg;
                font-style: normal;
                font-weight: normal;
                src: url('{{ storage_path('fonts/ipaexg.ttf') }}') format('truetype');
            }
            @font-face {
                font-family: ipaexg;
                font-style: bold;
                font-weight: bold;
                src: url('{{ storage_path('fonts/ipaexg.ttf') }}') format('truetype');
            }
            body {
                font-family: ipaexg !important;
            }
            @page {
                margin-top: 0px;
                margin-bottom: 0px;
                margin-right: 0px;
                margin-left: 10px;
            }
            @page {
                size : A4 portrait;
            }
            .top_page_title {
                font-size: 30px;
            }
            .info_parent {
                font-size: 0;
                margin-bottom: 5px;
                display:block;
            }
            .info_label {
                font-size: 10px;
                font-weight: bold;
                background-color: #000000;
                color:#ffffff;
                border:1px solid #000000;
                text-align: center;
                padding-left: 0.75rem; /* 12px */
                padding-right: 0.75rem; /* 12px */
                display : inline-block;
                width: 80px;
            }
            .info_text {
                font-size: 10px;
                font-weight: bold;
                border:1px solid #000000;
                padding-left: 0.75rem; /* 12px */
                padding-right: 0.75rem; /* 12px */
                display : inline-block;
                width: 110px;
            }
            .title {
                font-size: 30px;
                page-break-before:always; /* 改ページ */
            }
            table.kintai_table{
                border-collapse:collapse;
                font-size: 12px;
                margin-top: 10px;
            }
            table.kintai_table th{
                border:1px solid #000000;
                background:rgb(137, 212, 255);
                text-align: center;
                padding-left: 0.75rem; /* 12px */
                padding-right: 0.75rem; /* 12px */
            }
            table.kintai_table td{
                border:1px solid #000000;
                background:#ffffff;
                padding-left: 0.75rem; /* 12px */
                padding-right: 0.75rem; /* 12px */
                padding-top: 2px;
                padding-bottom: 2px;
                font-size: 10px;
            }
            .center {
                text-align: center;
            }
            .left {
                text-align: left;
            }
            .calc_field_parent_div {
                margin-top: 6px;
            }
            .calc_field_child_div {
                border-bottom: 1px solid#000000;
                margin-bottom: 5px;
            }
            .calc_field_1{
                font-size: 15px;
                margin-right: 80px;
                width: 100px;
                display: inline-block;
            }
            .calc_field_2{
                font-size: 15px;
                margin-right: 260px;
                width: 100px;
                display: inline-block;
            }
            .underline{
                border-bottom:1px solid #000000;
            }
            .div-flex-row{
                display: flex;
                flex-direction: row;
            }
        </style>
        <!-- Styles -->
        <link rel="stylesheet" href="{{ public_path('css/app.css') }}">
    </head>
    <body style="font-family: ipaexg">
        <!-- 表紙 -->
        <p class="top_page_title">{{ '勤怠表≪'.CarbonImmutable::parse($month)->isoFormat('Y年MM月').'≫' }}</p>
        <div class="info_parent">
            <span class="info_label">拠点</span>
            <span class="info_text">{{ $base['base']->base_name }}</span>
        </div>
        @foreach($base['total_employee'] as $employee_category_name => $total_employee)
            <div class="info_parent">
                <span class="info_label">{{ $employee_category_name }}</span>
                <span class="info_text">{{ $total_employee.'人' }}</span>
            </div>
        @endforeach
        <!-- 各従業員勤怠表 -->
        @foreach($kintais as $employee_id => $kintai)
            <p class="title">勤怠表</p>
            <div class="info_parent">
                <span class="info_label">拠点</span>
                <span class="info_text">{{ $kintai['base_name'] }}</span>
            </div>
            <div class="info_parent">
                <span class="info_label">従業員番号</span>
                <span class="info_text">{{ $kintai['employee_no'] }}</span>
            </div>
            <div class="info_parent">
                <span class="info_label">従業員区分</span>
                <span class="info_text">{{ $kintai['employee_category_name'] }}</span>
            </div>
            <div class="info_parent">
                <span class="info_label">従業員名</span>
                <span class="info_text">{{ $kintai['employee_name'] }}</span>
            </div>
            <div class="info_parent">
                <span class="info_label">稼働日数</span>
                <span class="info_text">{{ $kintai['working_days'].'日' }}</span>
            </div>
            <div class="div-flex-row">
                <div class="info_parent" style="display: inline-block; ">
                    <span class="info_label">総稼働時間</span>
                    <span class="info_text">{{ number_format($kintai['total_working_time'] / 60, 2).'時間' }}</span>
                </div>
                <div class="info_parent" style="display: inline-block; ">
                    <span class="info_label">総祝日稼働時間</span>
                    <span class="info_text">{{ number_format($kintai['national_holiday_total_working_time'] / 60, 2).'時間' }}</span>
                </div>
                <div class="info_parent" style="display: inline-block; ">
                    <span class="info_label">総特別稼働時間</span>
                    <span class="info_text">{{ number_format($over40[$employee_id]['total_special_working_time'] / 60, 2).'時間' }}</span>
                </div>
            </div>
            <div class="div-flex-row">
                <div class="info_parent" style="display: inline-block; ">
                    <span class="info_label">総残業時間</span>
                    <span class="info_text">{{ number_format((($kintai['total_over_time'] + (!isset($over40[$employee_id]) ? 0 : $over40[$employee_id]['total_over40'])) + $kintai['total_late_night_working_time']) / 60, 2).'時間' }}</span>
                </div>
                <!-- 総残業時間 - 深夜残業時間 + 深夜稼働時間 -->
                <div class="info_parent" style="display: inline-block;">
                    <span class="info_label">通常残業時間</span>
                    <span class="info_text">{{ number_format(((($kintai['total_over_time'] + (!isset($over40[$employee_id]) ? 0 : $over40[$employee_id]['total_over40'])) + $kintai['total_late_night_working_time']) - $kintai['total_late_night_over_time']) / 60, 2).'時間' }}</span>
                </div>
                <div class="info_parent" style="display: inline-block;">
                    <span class="info_label">深夜残業時間</span>
                    <span class="info_text">{{ number_format(($kintai['total_late_night_over_time']) / 60, 2).'時間' }}</span>
                </div>
            </div>
            <table class="kintai_table">
                <thead>
                    <tr>
                        <th>出勤日</th>
                        <th>出勤</th>
                        <th>退勤</th>
                        <th>休憩</th>
                        @if($base['base']->is_add_rest_available)
                            <th>追休</th>
                        @endif
                        <th>外出</th>
                        <th>戻り</th>
                        <th>稼働</th>
                        <th>特別</th>
                        <th>残業</th>
                        <th>早出</th>
                        <th>コメント</th>
                        @if($kintai['employee_category_id'] == App\Enums\EmployeeCategoryEnum::PART_TIME_EMPLOYEE)
                            <th>超過</th>
                        @endif
                        <!-- 第1営業所のみ表示 -->
                        @if($kintai['base_id'] == '01_1st' && $kintai['employee_category_id'] == App\Enums\EmployeeCategoryEnum::PART_TIME_EMPLOYEE)
                            <th>大洋</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($kintai['kintai'] as $work_day => $value)
                        <tr>
                            <td style="{{ CarbonImmutable::parse($work_day)->dayOfWeekIso >= 6 || isset($holidays[$work_day]) ? 'background-color: #CCFFFF' : '' }}" class="center">{{ CarbonImmutable::parse($work_day)->isoFormat('Y年MM月DD日(ddd)') }}</td>
                            <td style="{{ CarbonImmutable::parse($work_day)->dayOfWeekIso >= 6 || isset($holidays[$work_day]) ? 'background-color: #CCFFFF' : '' }}" class="center">{{ is_null($value) ? '' : substr($value->begin_time_adj, 0, 5) }}</td>
                            <td style="{{ CarbonImmutable::parse($work_day)->dayOfWeekIso >= 6 || isset($holidays[$work_day]) ? 'background-color: #CCFFFF' : '' }}" class="center">{{ is_null($value) ? '' : substr($value->finish_time_adj, 0, 5) }}{{-- {{ is_null($value) ? '' : (CarbonImmutable::parse(substr($value->finish_time_adj, 0, 5)) >= Carbon\CarbonImmutable::createFromTime(22, 15) ? ' 深' : '') }} --}}</td>
                            <td style="{{ CarbonImmutable::parse($work_day)->dayOfWeekIso >= 6 || isset($holidays[$work_day]) ? 'background-color: #CCFFFF' : '' }}" class="center">{{ !isset($value->finish_time_adj) ? '' : number_format($value->rest_time / 60, 2) }}</td>
                            @if($base['base']->is_add_rest_available)
                                <td style="{{ CarbonImmutable::parse($work_day)->dayOfWeekIso >= 6 || isset($holidays[$work_day]) ? 'background-color: #CCFFFF' : '' }}" class="center">{{ !isset($value->finish_time_adj) ? '' : number_format($value->add_rest_time / 60, 2) }}</td>
                            @endif
                            <td style="{{ CarbonImmutable::parse($work_day)->dayOfWeekIso >= 6 || isset($holidays[$work_day]) ? 'background-color: #CCFFFF' : '' }}" class="center">{{ is_null($value) ? '' : substr($value->out_time_adj, 0, 5) }}</td>
                            <td style="{{ CarbonImmutable::parse($work_day)->dayOfWeekIso >= 6 || isset($holidays[$work_day]) ? 'background-color: #CCFFFF' : '' }}" class="center">{{ is_null($value) ? '' : substr($value->return_time_adj, 0, 5) }}</td>
                            <td style="{{ CarbonImmutable::parse($work_day)->dayOfWeekIso >= 6 || isset($holidays[$work_day]) ? 'background-color: #CCFFFF' : '' }}" class="center">{{ !isset($value->finish_time_adj) ? '' : number_format($value->working_time / 60, 2) }}{{ !isset($value->finish_time_adj) ? '' : (number_format($value->working_time / 60, 2) <= 6.25 && $kintai['employee_category_id'] == App\Enums\EmployeeCategoryEnum::FULL_TIME_EMPLOYEE ? ' 少' : '') }}</td>
                            <td style="{{ CarbonImmutable::parse($work_day)->dayOfWeekIso >= 6 || isset($holidays[$work_day]) ? 'background-color: #CCFFFF' : '' }}" class="center">{{ !isset($value->finish_time_adj) ? '' : number_format($value->special_working_time / 60, 2) }}</td>
                            <td style="{{ CarbonImmutable::parse($work_day)->dayOfWeekIso >= 6 || isset($holidays[$work_day]) ? 'background-color: #CCFFFF' : '' }}" class="center">{{ !isset($value->finish_time_adj) ? '' : number_format(($value->over_time + $value->late_night_working_time) / 60, 2) }}</td>
                            <td style="{{ CarbonImmutable::parse($work_day)->dayOfWeekIso >= 6 || isset($holidays[$work_day]) ? 'background-color: #CCFFFF' : '' }}" class="center">{{ is_null($value) ? '' : ($value->is_early_worked == 1 ? '○' : '') }}</td>
                            <td style="{{ CarbonImmutable::parse($work_day)->dayOfWeekIso >= 6 || isset($holidays[$work_day]) ? 'background-color: #CCFFFF' : '' }}" class="left">{{ is_null($value) ? '' : $value->comment }}</td>
                            @if($kintai['employee_category_id'] == App\Enums\EmployeeCategoryEnum::PART_TIME_EMPLOYEE)
                                <td style="{{ CarbonImmutable::parse($work_day)->dayOfWeekIso >= 6 || isset($holidays[$work_day]) ? 'background-color: #CCFFFF' : '' }}" class="center">{{ CarbonImmutable::parse($work_day)->isSunday() && isset($over40[$employee_id]) ? (isset($over40[$employee_id][$work_day]) ? ($over40[$employee_id][$work_day]->over40 > 0 ? number_format($over40[$employee_id][$work_day]->over40 / 60, 2) : '0.00') : '0.00') : '' }}</td>
                            @endif
                            <!-- 第1営業所のみ表示 -->
                            @if($kintai['base_id'] == '01_1st' && $kintai['employee_category_id'] == App\Enums\EmployeeCategoryEnum::PART_TIME_EMPLOYEE)
                                <td style="{{ CarbonImmutable::parse($work_day)->dayOfWeekIso >= 6 || isset($holidays[$work_day]) ? 'background-color: #CCFFFF' : '' }}" class="center">{{ isset($taiyo_working_times[$employee_id][$work_day]) ? '○' : '' }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- 勤務表下部の計算欄 -->
            <div class="calc_field_parent_div">
                <div class="calc_field_child_div">
                    <p><p class="calc_field_1">時給単価</p><p class="calc_field_1">×</p><p class="calc_field_1">=</p></p>
                    <p class="underline"><p class="calc_field_1">時給単価</p><p class="calc_field_1">×</p><p class="calc_field_1">=</p></p>
                    <p class="underline"><p class="calc_field_1">時給単価</p><p class="calc_field_1">×</p><p class="calc_field_1">=</p></p>
                    <p class="underline"><p class="calc_field_1">時給単価</p><p class="calc_field_1">×</p><p class="calc_field_1">=</p></p>
                    <p class="underline"><p class="calc_field_1">交通費</p><p class="calc_field_1">×</p><p class="calc_field_1">=</p></p>
                </div>
                <p><p class="calc_field_2">合計</p><p class="calc_field_1">=</p></p>
            </div>
            <!-- 応援稼働がある場合のみ出力 -->
            @if(count($kintai['support_working_time']) != 0)
                <p class="title">応援稼働時間表</p>
                <div class="info_parent">
                    <span class="info_label">年月</span>
                    <span class="info_text">{{ CarbonImmutable::parse($month)->isoFormat('Y年MM月') }}</span>
                </div>
                <div class="info_parent">
                    <span class="info_label">拠点</span>
                    <span class="info_text">{{ $kintai['base_name'] }}</span>
                </div>
                <div class="info_parent">
                    <span class="info_label">従業員番号</span>
                    <span class="info_text">{{ $kintai['employee_no'] }}</span>
                </div>
                <div class="info_parent">
                    <span class="info_label">従業員名</span>
                    <span class="info_text">{{ $kintai['employee_name'] }}</span>
                </div>
                <table class="kintai_table">
                    <thead>
                        <tr>
                            <th>応援先拠点名</th>
                            <th>稼働時間</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kintai['support_working_time'] as $support_working_time)
                            <tr>
                                <td>{{ $support_working_time->base_name }}</td>
                                <td style="text-align: right;">{{ number_format($support_working_time->total_customer_working_time / 60, 2).'時間' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach
    </body>
</html>
