@vite(['resources/css/punch_finish_tab.css', 'resources/js/punch/punch_finish_input.js', 'resources/js/punch/punch_finish_tab.js'])

<x-app-layout>
    <x-page-header content="修正打刻 > 時間入力"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap mb-2">
            <a href="{{ session('back_url_2') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
            <p class="ml-auto text-4xl py-2">{{ CarbonImmutable::parse($kintai->work_day)->isoFormat('YYYY年MM月DD日(ddd)') }}</p>
        </div>
        <form method="POST" id="punch_enter_form" action="{{ route('punch_update.enter') }}" class="m-0">
            @csrf
            <div class="grid grid-cols-12">
                <!-- 概要情報を表示 -->
                <div class="col-span-12 grid grid-cols-12 text-4xl py-3 bg-theme-sub rounded-t-lg">
                    <p id="employee_name" class="col-start-1 col-span-4 pl-3">{{ $kintai->employee->employee_last_name.$kintai->employee->employee_first_name }}<span class="text-xl ml-3">さん</span></p>
                    <p class="col-start-5 col-span-4 text-center">出勤 {{ substr(session('begin_time_adj'), 0, 5) }}</p>
                    <p class="col-start-9 col-span-4 text-center">退勤 {{ substr(session('finish_time_adj'), 0, 5) }}</p>
                </div>
                <div class="col-start-1 col-span-12 border-2 border-theme-sub bg-white">
                    <div class="py-5 grid grid-cols-12">
                        <p class="col-span-1 text-xl text-center pt-1">外出(時)</p>
                        <p class="col-span-2 text-2xl text-left">{{ number_format(session('out_return_time') / 60, 2) }}</p>
                        <p class="col-span-1 text-xl text-center pt-1">休憩(分)</p>
                        <input id="rest_time" name="rest_time" class="col-span-2 text-2xl text-left bg-transparent" readonly>
                        <!-- 追加休憩取得時間を表示(追加休憩時間が有効かつパートのみ) -->
                        @if($add_rest_available && $employee->employee_category_id == App\Enums\EmployeeCategoryEnum::PART_TIME_EMPLOYEE)
                            <p class="col-span-1 text-xl text-center pt-1">追加休憩(分)</p>
                            <input id="add_rest_time_disp" name="add_rest_time_disp" class="col-span-2 text-2xl text-left bg-transparent" readonly>
                        @endif
                        <p class="col-span-1 text-xl text-center pt-1">稼働(時)</p>
                        <input id="working_time" name="working_time" class="col-span-2 text-2xl text-left bg-transparent" readonly>
                        <input type="hidden" id="org_rest_time" name="org_rest_time" value="{{ $default_rest_time }}">
                        <input type="hidden" id="org_working_time" value="{{ session('working_time') }}">
                        <input type="hidden" name="employee_id" value="{{ $kintai->employee_id }}">
                        <input type="hidden" id="rest_related_select_mode" value="{{ $base->rest_related_select_mode }}">
                        <input type="hidden" id="law_rest_time" value="{{ $law_rest_time }}">
                    </div>
                </div>
                <!-- 休憩取得時間を表示 -->
                <p class="col-start-1 col-span-12 text-4xl py-3 pl-3 bg-theme-sub">休憩取得時間</p>
                <div class="col-start-1 col-span-12 border-2 border-theme-sub bg-white">
                    <div class="p-5 grid grid-cols-12 gap-4">
                        <div id="rest_time_div" class="col-span-2"></div>
                    </div>
                </div>
                <!-- 追加休憩取得時間を表示(追加休憩時間が有効かつパートのみ) -->
                @if($add_rest_available && $employee->employee_category_id == App\Enums\EmployeeCategoryEnum::PART_TIME_EMPLOYEE)
                    <p class="col-start-1 col-span-12 text-4xl py-3 pl-3 bg-theme-sub">追加休憩取得時間</p>
                    <div class="col-start-1 col-span-12 border-2 border-theme-sub bg-white">
                        <div class="p-5 grid grid-cols-12 gap-4">
                            @foreach($add_rest_times as $add_rest_time)
                                <div class="col-span-2">
                                    <input type="radio" name="add_rest_time" id="{{ $add_rest_time['minute'].'_add_rest_time' }}" value="{{ $add_rest_time['minute'] }}" class="add_rest_time_select hidden" {{ $add_rest_time['minute'] == $kintai->add_rest_time ? 'checked' : '' }}>
                                    <label id="{{ $add_rest_time['minute'].'_add_rest_time_label' }}" for="{{ $add_rest_time['minute'].'_add_rest_time' }}" class="cursor-pointer flex flex-col w-full max-w-lg mx-auto text-center border-2 rounded-lg border-gray-900 p-2 text-2xl">{{ $add_rest_time['text1'] }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <!-- 入力した荷主稼働時間情報を表示 -->
                <div class="col-span-12 grid grid-cols-12 text-4xl py-3 bg-theme-sub">
                    <p class="col-span-4 pl-3">荷主稼働時間</p>
                    <p class="col-start-8 col-span-5 text-right pr-3">残り入力時間：<span id="input_time_left"></span></p>
                </div>
                <div class="col-start-1 col-span-12 border-2 border-theme-sub rounded-b-lg grid grid-cols-12 bg-white">
                    <div id="input_working_time_info" class="p-5 col-span-12 grid grid-cols-12 gap-4">
                        @foreach($kintai_details as $kintai_detail)
                            <button type="button" id="working_time_input_{{ $kintai_detail->customer_id }}" class="working_time_info_delete col-span-4 py-5 text-center bg-blue-200 text-xl rounded-lg cursor-pointer working_time_input_{{ $kintai_detail->customer_id }}">
                                {{ $kintai_detail->customer_name }}<br>{{ number_format($kintai_detail->customer_working_time / 60, 2) }}
                            </button>
                            <input type="hidden" id="working_time_input_{{ $kintai_detail->customer_id }}_hidden" class="working_time_input working_time_input_{{ $kintai_detail->customer_id }}" name="working_time_input[{{ $kintai_detail->customer_id }}]" value="{{ number_format($kintai_detail->customer_working_time / 60, 2) }}">
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- 荷主情報のタブを表示 -->
            <x-punch.customer-tab :customers="$customers" :customergroups="$customer_groups" :supportbases="$support_bases"></x-customer-tab>
            <button type="button" id="punch_finish_enter" class="punch_enter w-full text-center bg-blue-200 py-8 text-4xl rounded-lg mt-3">入力完了</button>
            <input type="hidden" id="is_chief_approvaled" name="is_chief_approvaled" value="0">
            <input type="hidden" id="is_law_violated" name="is_law_violated" value="0">
        </form>
        <!-- 時間入力モーダル -->
        <x-punch.working_time_input_modal />
        <!-- 打刻確認モーダル -->
        <x-punch.punch-confirm-modal proc="修正" :earlyWorkSelectInfo="null" :earlyWorkAvailable="0" message="null" />
        <!-- 休憩取得時間確認モーダル -->
        <x-punch.rest-time-check-modal :employeeFullName="$kintai->employee->employee_last_name.$kintai->employee->employee_first_name" :defaultRestTime="$default_rest_time" />
        <!-- 休憩取得時間変更モーダル -->
        <x-punch.rest-time-change-modal :employeeFullName="$kintai->employee->employee_last_name.$kintai->employee->employee_first_name" :defaultRestTime="$default_rest_time" :restTimes="session('rest_times')" />
        <input type="hidden" id="lunch_break_check_message" value="none">
    </div>
</x-app-layout>