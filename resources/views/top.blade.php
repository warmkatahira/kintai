<x-app-layout>
    <x-page-header content="TOP"/>
    <div class="my-5">
        <div class="flex flex-row justify-center">
            <a href="{{ route('punch.index') }}" class="text-5xl w-full text-center p-20 bg-theme-sub">打刻はこちら</a>
        </div>
        <div class="flex flex-col w-48 mt-5">
            <p class="text-xl text-center py-2 bg-black text-white">退勤処理漏れ</p>
            <p class="text-3xl text-center py-6 bg-white">{{ number_format($no_finish_kintai_count) }}</p>
        </div>
    </div>
</x-app-layout>