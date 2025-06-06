<!-- 勤怠一覧 -->
<div class="scroll_wrap flex flex-grow overflow-scroll">
    <div class="kintai_mgt_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-sm block whitespace-nowrap">
            <thead>
                <tr class="text-left text-white bg-gray-600 sticky top-0">
                    @can('isBaseCheckAvailable')
                        <th id="all_check" class="font-thin py-1 px-2 text-center"><i class="las la-check-square la-lg"></i></th>
                    @endcan
                    <th class="font-thin py-3 px-2 text-center">詳細</th>
                    <th class="font-thin py-3 px-2 text-center">出勤日</th>
                    <th class="font-thin py-3 px-2 text-center">拠点</th>
                    <th class="font-thin py-3 px-2 text-center">従業員区分</th>
                    <th class="font-thin py-3 px-2 text-center">従業員名</th>
                    <th class="font-thin py-3 px-2 text-center">出勤時間</th>
                    <th class="font-thin py-3 px-2 text-center">退勤時間</th>
                    <th class="font-thin py-3 px-2 text-center">外出時間</th>
                    <th class="font-thin py-3 px-2 text-center">戻り時間</th>
                    <th class="font-thin py-3 px-2 text-center">休憩取得時間</th>
                    <th class="font-thin py-3 px-2 text-center">休憩未取得時間</th>
                    @if(Gate::check('isAddRestTimeDispAvailable') || $addRestAvailable)
                        <th class="font-thin py-3 px-2 text-center">追加休憩取得時間</th>
                    @endif
                    <th class="font-thin py-3 px-2 text-center">稼働時間</th>
                    <th class="font-thin py-3 px-2 text-center">残業時間</th>
                    <th class="font-thin py-3 px-2 text-center">深夜残業時間</th>
                    <th class="font-thin py-3 px-2 text-center">深夜稼働時間</th>
                    <th class="font-thin py-3 px-2 text-center">所長承認</th>
                    <th class="font-thin py-3 px-2 text-center">法令違反</th>
                    <th class="font-thin py-3 px-2 text-center">拠点確認者</th>
                    <th class="font-thin py-3 px-2 text-center">拠点確認日時</th>
                    <th class="font-thin py-3 px-2 text-center">コメント</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($kintais as $kintai)
                    <tr class="text-left hover:bg-theme-sub cursor-default">
                        @can('isBaseCheckAvailable')
                            <td class="py-1 px-2 border text-center">
                                @can('isBaseCheckAvailableKintai', $kintai)
                                    <input type="checkbox" name="chk[]" value="{{ $kintai->kintai_id }}" form="base_check_form">
                                @endcan
                            </td>
                        @endcan
                        <td class="py-1 px-2 border text-center">
                            <a href="{{ route('kintai_mgt.detail', ['kintai_id' => $kintai->kintai_id]) }}" class="text-center border border-blue-500 bg-blue-100 text-blue-500 text-xs px-2 py-1">詳細</a>
                        </td>
                        <td class="py-1 px-2 border text-center">{{ CarbonImmutable::parse($kintai->work_day)->isoFormat('YYYY年MM月DD日(ddd)') }}</td>
                        <td class="py-1 px-2 border">{{ $kintai->employee->base->base_name }}</td>
                        <td class="py-1 px-2 border text-center">{{ $kintai->employee_category_name }}</td>
                        <td class="py-1 px-2 border">{{ $kintai->employee_last_name.' '.$kintai->employee_first_name }}</td>
                        <td class="py-1 px-2 border text-center">{{ substr($kintai->begin_time_adj, 0, 5) }}</td>
                        <td class="py-1 px-2 border text-center">{{ substr($kintai->finish_time_adj, 0, 5) }}</td>
                        <td class="py-1 px-2 border text-center">{{ substr($kintai->out_time_adj, 0, 5) }}</td>
                        <td class="py-1 px-2 border text-center">{{ substr($kintai->return_time_adj, 0, 5) }}</td>
                        <td class="py-1 px-2 border text-center">@if(!is_null($kintai->finish_time)){{ number_format($kintai->rest_time / 60, 2) }}@endif</td>
                        <td class="py-1 px-2 border text-center">@if(!is_null($kintai->finish_time)){{ number_format($kintai->no_rest_time / 60, 2) }}@endif</td>
                        @if(Gate::check('isAddRestTimeDispAvailable') || $addRestAvailable)
                            <td class="py-1 px-2 border text-center">@if(!is_null($kintai->finish_time)){{ number_format($kintai->add_rest_time / 60, 2) }}@endif</td>
                        @endif
                        <td class="py-1 px-2 border text-center">@if(!is_null($kintai->finish_time)){{ number_format($kintai->working_time / 60, 2) }}@endif</td>
                        @if(Gate::check('isShortTimeInfoAvailable') || $kintai->over_time_start == 0)
                            <td class="py-1 px-2 border text-center {{ $kintai->over_time == 0 ? '' : 'bg-pink-200' }}">@if(!is_null($kintai->finish_time)){{ number_format($kintai->over_time / 60, 2) }}@endif</td>
                        @else
                            <td class="py-1 px-2 border text-center">@if(!is_null($kintai->finish_time)){{ number_format(0 / 60, 2) }}@endif</td>
                        @endif
                        <td class="py-1 px-2 border text-center {{ $kintai->late_night_over_time == 0 ? '' : 'bg-pink-200' }}">@if(!is_null($kintai->finish_time)){{ number_format($kintai->late_night_over_time / 60, 2) }}@endif</td>
                        <td class="py-1 px-2 border text-center {{ $kintai->late_night_working_time == 0 ? '' : 'bg-pink-200' }}">@if(!is_null($kintai->finish_time)){{ number_format($kintai->late_night_working_time / 60, 2) }}@endif</td>
                        <td class="py-1 px-2 border text-center {{ $kintai->is_chief_approvaled == 0 ? '' : 'bg-pink-200' }}">{{ $kintai->is_chief_approvaled ? '○' : '' }}</td>
                        <td class="py-1 px-2 border text-center {{ $kintai->is_law_violated == 0 ? '' : 'bg-pink-200' }}">{{ $kintai->is_law_violated ? '○' : '' }}</td>
                        
                        
                        <td class="py-1 px-2 border text-center">@if(!is_null($kintai->base_checked_at)) {{ $kintai->user->last_name.' '.$kintai->user->first_name }} @endif</td>
                        <td class="py-1 px-2 border text-center">@if(!is_null($kintai->base_checked_at)){{ CarbonImmutable::parse($kintai->base_checked_at)->isoFormat('Y年MM月DD日 HH時mm分ss秒') }}@endif</td>
                        <td class="py-1 px-2 border text-left">{{ $kintai->comment }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>