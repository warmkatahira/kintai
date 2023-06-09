@vite(['resources/js/punch/punch_finish.js'])

<x-app-layout>
    <x-page-header content="退勤打刻"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap">
            <a href="{{ route('punch.index') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
            <x-punch.clock />
        </div>
        <form method="GET" id="punch_enter_form" action="{{ route('punch_finish.input') }}" class="m-0">
            <!-- 従業員名ボタンを表示 -->
            <x-punch.punch-employee-btn :employees="$employees" key="kintai_id" />
        </form>
    </div>
    <!-- 打刻完了ポップアップ画面 -->
    <x-punch.punch-complete-popup/>
</x-app-layout>
