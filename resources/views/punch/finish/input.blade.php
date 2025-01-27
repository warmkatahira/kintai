@vite(['resources/css/punch_finish_tab.css', 'resources/js/punch/punch_finish_input.js', 'resources/js/punch/punch_finish_tab.js'])

<x-app-layout>
    <x-page-header content="退勤打刻 > 時間入力"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap mb-2">
            <a href="{{ route('punch_finish.index') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
            <p class="ml-auto text-4xl py-2">{{ CarbonImmutable::now()->isoFormat('YYYY年MM月DD日(ddd)') }}</p>
        </div>
        <form method="POST" id="punch_enter_form" action="{{ route('punch_finish.enter') }}" class="m-0">
            @csrf
            <div class="grid grid-cols-12">
                <!-- 概要情報を表示 -->
                <div class="col-span-12 grid grid-cols-12 text-4xl py-3 bg-theme-sub rounded-t-lg">
                    <p id="employee_name" class="col-start-1 col-span-4 pl-3">{{ $kintai->employee->employee_last_name.$kintai->employee->employee_first_name }}<span class="text-xl ml-3">さん</span></p>
                    <p class="col-start-5 col-span-4 text-center">出勤 {{ substr($kintai->begin_time_adj, 0, 5) }}</p>
                    <p class="col-start-9 col-span-4 text-center">退勤 {{ substr($finish_time_adj, 0, 5) }}</p>
                </div>
                <div class="col-start-1 col-span-12 border-2 border-theme-sub bg-white">
                    <div class="py-5 grid grid-cols-12">
                        <p class="col-span-1 text-xl text-center pt-1">外出(時)</p>
                        <p class="col-span-2 text-2xl text-left">{{ number_format($kintai->out_return_time / 60, 2) }}</p>
                        <p class="col-span-1 text-xl text-center pt-1">休憩(分)</p>
                        <input id="rest_time" name="rest_time" class="col-span-2 text-2xl text-left bg-transparent" readonly>
                        <!-- 追加休憩取得時間を表示(追加休憩時間が有効かつパートのみ) -->
                        @if($add_rest_available && $employee->employee_category_id == App\Enums\EmployeeCategoryEnum::PART_TIME_EMPLOYEE)
                            <p class="col-span-1 text-xl text-center pt-1">追加休憩(分)</p>
                            <input id="add_rest_time_disp" name="add_rest_time_disp" class="col-span-2 text-2xl text-left bg-transparent" readonly>
                        @endif
                        <p class="col-span-1 text-xl text-center pt-1">稼働(時)</p>
                        <input id="working_time" name="working_time" class="col-span-2 text-2xl text-left bg-transparent" readonly>
                        <input type="hidden" id="org_rest_time" name="org_rest_time" value="{{ $rest_time }}">
                        <input type="hidden" id="org_working_time" value="{{ $working_time }}">
                        <input type="hidden" name="finish_time_adj" value="{{ $finish_time_adj }}">
                        <input type="hidden" name="kintai_id" value="{{ $kintai->kintai_id }}">
                        <input type="hidden" name="finish_time" value="{{ $finish_time }}">
                        <input type="hidden" id="rest_related_select_mode" value="{{ $base->rest_related_select_mode }}">
                    </div>
                </div>
                @if($base->rest_related_select_mode == 'no_rest')
                    <!-- 休憩未取得時間を表示 -->
                    <p class="col-start-1 col-span-12 text-4xl py-3 pl-3 bg-theme-sub">休憩未取得時間</p>
                    <div class="col-start-1 col-span-12 border-2 border-theme-sub bg-white">
                        <div class="p-5 grid grid-cols-12 gap-4">
                            @foreach($no_rest_times as $no_rest_time)
                                <div class="col-span-2">
                                    <input type="radio" name="no_rest_time" id="{{ $no_rest_time['minute'] }}" value="{{ $no_rest_time['minute'] }}" class="no_rest_time_select hidden" {{ $no_rest_time['minute'] == '0' ? 'checked' : '' }}>
                                    <label id="{{ $no_rest_time['minute'].'_label' }}" for="{{ $no_rest_time['minute'] }}" class="cursor-pointer flex flex-col w-full max-w-lg mx-auto text-center border-2 rounded-lg border-gray-900 p-2 text-2xl">{{ $no_rest_time['text1'] }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if($base->rest_related_select_mode == 'rest')
                    <!-- 休憩取得時間を表示 -->
                    <p class="col-start-1 col-span-12 text-4xl py-3 pl-3 bg-theme-sub">休憩取得時間</p>
                    <div class="col-start-1 col-span-12 border-2 border-theme-sub bg-white">
                        <div class="p-5 grid grid-cols-12 gap-4">
                            @foreach($rest_times as $time)
                                <div class="col-span-2">
                                    <input type="radio" name="rest_time_select" id="{{ $time['minute'] }}" value="{{ $time['minute'] }}" class="rest_time_select hidden" {{ $time['minute'] == $rest_time ? 'checked' : '' }}>
                                    <label id="{{ $time['minute'].'_label' }}" for="{{ $time['minute'] }}" class="cursor-pointer flex flex-col w-full max-w-lg mx-auto text-center border-2 rounded-lg border-gray-900 p-2 text-2xl">{{ $time['text1'] }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <!-- 追加休憩取得時間を表示(追加休憩時間が有効かつパートのみ) -->
                @if($add_rest_available && $employee->employee_category_id == App\Enums\EmployeeCategoryEnum::PART_TIME_EMPLOYEE)
                    <p class="col-start-1 col-span-12 text-4xl py-3 pl-3 bg-theme-sub">追加休憩取得時間</p>
                    <div class="col-start-1 col-span-12 border-2 border-theme-sub bg-white">
                        <div class="p-5 grid grid-cols-12 gap-4">
                            @foreach($add_rest_times as $add_rest_time)
                                <div class="col-span-2">
                                    <input type="radio" name="add_rest_time" id="{{ $add_rest_time['minute'].'_add_rest_time' }}" value="{{ $add_rest_time['minute'] }}" class="add_rest_time_select hidden" {{ $add_rest_time['minute'] == '0' ? 'checked' : '' }}>
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
                    <div id="input_working_time_info" class="p-5 col-span-12 grid grid-cols-12 gap-4"></div>
                </div>
            </div>
            <!-- 荷主情報のタブを表示 -->
            <x-punch.customer-tab :customers="$customers" :customergroups="$customer_groups" :supportbases="$support_bases"></x-customer-tab>
            <button type="button" id="punch_finish_enter" class="punch_enter w-full text-center bg-blue-200 py-8 text-4xl rounded-lg mt-3">入力完了</button>
        </form>
        <!-- 時間入力モーダル -->
        <x-punch.working_time_input_modal/>
        <!-- 打刻確認モーダル -->
        <x-punch.punch-confirm-modal proc="退勤" :earlyWorkSelectInfo="null" :earlyWorkAvailable="0"/>
    </div>
</x-app-layout>
