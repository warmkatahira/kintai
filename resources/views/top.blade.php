<x-app-layout>
    <x-page-header content="TOP"/>
    <div class="my-5">
        <div class="flex flex-row justify-evenly">
            <div class="flex flex-col w-48">
                <p class="text-xl text-center py-2 bg-black text-white">現在出勤中</p>
                <p class="text-3xl text-center py-6 bg-white">{{ number_format($now_begin_kintai_count) }}</p>
            </div>
            <div class="flex flex-col w-48">
                <p class="text-xl text-center py-2 bg-black text-white">退勤処理漏れ</p>
                <p class="text-3xl text-center py-6 bg-white">{{ number_format($no_finish_kintai_count) }}</p>
            </div>
        </div>
        <div class="flex flex-row justify-center mt-5">
            <a href="{{ route('punch.index') }}" class="text-5xl text-center py-20 px-80 bg-theme-sub">打刻はこちら</a>
        </div>
    </div>
</x-app-layout>