@vite(['resources/js/punch/punch_common.js'])

<x-app-layout>
    <x-page-header content="出勤打刻"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap">
            <a href="{{ route('punch.index') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
            <x-punch.clock />
        </div>
        <form method="POST" id="punch_enter_form" action="{{ route('punch_begin.enter') }}" class="m-0">
            @csrf
            <!-- 早出が可能な場合のみボタンを表示 -->
            @if($early_work_available)
                <x-punch.punch-begin-type :db="0" />
            @endif
            <!-- 従業員名ボタンを表示 -->
            <x-punch.punch-employee-btn :employees="$employees" key="employee_id" />
            <!-- 打刻確認モーダル -->
            <x-punch.punch-confirm-modal proc="出勤" :earlyWorkSelectInfo="$early_work_select_info" :earlyWorkAvailable="$early_work_available" message="null" />
            <input type="hidden" id="lunch_break_check_message" value="none">
        </form>
    </div>
    <!-- 打刻完了ポップアップ画面 -->
    <x-punch.punch-complete-popup/>
</x-app-layout>