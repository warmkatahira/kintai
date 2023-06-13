<x-app-layout>
    <x-page-header content="従業員詳細"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap mb-2">
            <a href="{{ session('back_url_1') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
            @can('isEmployeeMgtAvailable')
                <a href="{{ route('employee_update.index', ['employee_id' => $employee->employee_id]) }}" class="w-40 text-xl py-4 rounded-lg text-center bg-blue-200 ml-auto">更新</a>
            @endcan
        </div>
        <!-- 従業員情報 -->
        <div class="flex flex-col">
            <p class="border-l-8 border-theme-sub text-xl pl-3 my-3">従業員情報</p>
            <div class="flex flex-row">
                <x-detail-div label="拠点" :value="$employee->base->base_name" />
                <x-detail-div label="従業員区分" :value="$employee->employee_category->employee_category_name" />
            </div>
            <div class="flex flex-row">
                <x-detail-div label="従業員番号" :value="$employee->employee_no" />
                <x-detail-div label="従業員名" :value="$employee->employee_last_name.' '.$employee->employee_first_name" />
            </div>
            <div class="flex flex-row">
                <x-detail-div label="月間稼働可能時間" :value="number_format($employee->monthly_workable_time, 2)" />
                <x-detail-div label="残業開始時間" :value="number_format($employee->over_time_start, 2)" />
            </div>
        </div>
        <!-- 当月稼働情報 -->
        <div class="flex flex-col">
            <p class="border-l-8 border-theme-sub text-xl pl-3 my-3">当月稼働情報</p>
            <div class="flex flex-row">
                <x-detail-div label="稼働日数" :value="$working_days.' 日'" />
                <x-detail-div label="総稼働時間" :value="number_format($total_working_time / 60, 2)" />
                <x-detail-div label="総残業時間" :value="number_format($total_over_time / 60, 2)" />
            </div>
        </div>
        <!-- 当月稼働時間トップ3 -->
        <div class="flex flex-col w-4/12">
            <p class="border-l-8 border-theme-sub text-xl pl-3 my-3">当月稼働時間トップ3</p>
            @foreach($customer_working_time as $key => $data)
                <div class="flex flex-row border-b-4 border-theme-sub">
                    <p class=""><span class="mr-5">{{ ($key + 1).'位' }}</span>{{ $data->customer_name }}</p>
                    <p class="ml-auto text-right">{{ number_format(($data->total_customer_working_time / 60), 2).' 時間（'.number_format((($data->total_customer_working_time / $total_working_time) * 100),2).' %）' }}</p>
                </div>
            @endforeach
        </div>
        <!-- 勤怠表 -->
        <p class="border-l-8 border-theme-sub text-xl pl-3 my-3">勤怠表</p>
        <div class="scroll_wrap flex flex-grow overflow-scroll mt-2">
            <div class="table_parent_div bg-white overflow-x-auto overflow-y-auto border border-gray-600">
                <table class="text-sm block whitespace-nowrap">
                    <thead>
                        <tr class="text-center text-white bg-gray-600 border-gray-600 sticky top-0">
                            <th class="font-thin p-2 px-2 w-2/12">出勤日</th>
                            <th class="font-thin p-2 px-2 w-1/12">出勤</th>
                            <th class="font-thin p-2 px-2 w-1/12">退勤</th>
                            <th class="font-thin p-2 px-2 w-1/12">休憩取得</th>
                            <th class="font-thin p-2 px-2 w-1/12">休憩未取得</th>
                            @if($add_rest_available)
                                <th class="font-thin p-2 px-2 w-1/12">追加休憩取得</th>
                            @endif
                            <th class="font-thin p-2 px-2 w-1/12">外出</th>
                            <th class="font-thin p-2 px-2 w-1/12">戻り</th>
                            <th class="font-thin p-2 px-2 w-1/12">稼働</th>
                            <th class="font-thin p-2 px-2 w-1/12">残業</th>
                            <th class="font-thin p-2 px-2 w-1/12">コメント</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kintais[$employee->employee_id]['kintai'] as $work_day => $value)
                            <tr class="hover:bg-theme-sub text-center">
                                <td class="p-1 px-2 border">{{ \Carbon\CarbonImmutable::parse($work_day)->isoFormat('YYYY年MM月DD日(ddd)') }}</td>
                                <td class="p-1 px-2 border">{{ is_null($value) ? '' : substr($value->begin_time_adj, 0, 5) }}</td>
                                <td class="p-1 px-2 border">{{ is_null($value) ? '' : substr($value->finish_time_adj, 0, 5) }}</td>
                                <td class="p-1 px-2 border">{{ is_null($value) ? '' : number_format($value->rest_time / 60, 2) }}</td>
                                <td class="p-1 px-2 border">{{ is_null($value) ? '' : number_format($value->no_rest_time / 60, 2) }}</td>
                                @if($add_rest_available)
                                    <td class="p-1 px-2 border">{{ is_null($value) ? '' : number_format($value->add_rest_time / 60, 2) }}</td>
                                @endif
                                <td class="p-1 px-2 border">{{ is_null($value) ? '' : substr($value->out_time_adj, 0, 5) }}</td>
                                <td class="p-1 px-2 border">{{ is_null($value) ? '' : substr($value->return_time_adj, 0, 5) }}</td>
                                <td class="p-1 px-2 border">{{ is_null($value) ? '' : number_format($value->working_time / 60, 2) }}</td>
                                <td class="p-1 px-2 border {{ is_null($value) ? '' : ($value->over_time == 0 ? '' : 'bg-pink-200') }}">{{ is_null($value) ? '' : number_format($value->over_time / 60, 2) }}</td>
                                <td class="p-1 px-2 border text-left">{{ is_null($value) ? '' : $value->comment }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
