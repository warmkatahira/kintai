<x-app-layout>
    <x-page-header content="打刻"/>
    <div class="flex justify-evenly my-5">
        <a href="{{ route('punch_begin.index') }}" class="bg-black text-white text-5xl py-10 px-20">出勤</a>
        <a href="{{ route('punch_finish.index') }}" class="bg-black text-white text-5xl py-10 px-20">退勤</a>
        <a href="{{ route('punch_out.index') }}" class="bg-black text-white text-5xl py-10 px-20">外出</a>
        <a href="{{ route('punch_return.index') }}" class="bg-black text-white text-5xl py-10 px-20">戻り</a>
    </div>
    <div class="flex justify-evenly my-10">
        <a href="{{ route('today_kintai.index') }}" class="bg-black text-white text-5xl py-10 px-20">今日の勤怠</a>
        <a href="{{ route('this_month_kintai.index') }}" class="bg-black text-white text-5xl py-10 px-20">今月の勤怠</a>
    </div>
    <!-- 打刻完了ポップアップ画面 -->
    <x-punch.punch-complete-popup/>
</x-app-layout>