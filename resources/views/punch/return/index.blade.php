@vite(['resources/js/punch/punch_common.js'])

<x-app-layout>
    <x-page-header content="戻り打刻"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap">
            <a href="{{ route('punch.index') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
            <x-punch.clock />
        </div>
        <form method="POST" id="punch_enter_form" action="{{ route('punch_return.enter') }}" class="m-0">
            @csrf
            <!-- 従業員名ボタンを表示 -->
            <x-punch.punch-employee-btn :employees="$employees" key="kintai_id" />
            <!-- 打刻確認モーダル -->
            <x-punch.punch-confirm-modal proc="戻り" :earlyWorkSelectInfo="null" :earlyWorkAvailable="0" :message="$message" />
            <input type="hidden" id="lunch_break_check_message" value="{{ $message }}">
        </form>
    </div>
</x-app-layout>