<x-app-layout>
    <x-page-header content="今月の勤怠"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap">
            <a href="{{ route('punch.index') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
        </div>
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 grid grid-cols-12 gap-4 mt-5">
                @foreach($month_kintais as $month_kintai)
                    <a href="{{ route('this_month_kintai.detail', ['employee_id' => $month_kintai->employee_id]) }}" class="col-span-3 grid grid-cols-12 rounded-lg px-8 py-4 bg-theme-sub text-xl">
                        <p class="col-span-12 text-center text-3xl">{{ $month_kintai->employee_last_name.$month_kintai->employee_first_name }}</p>
                        <div class="col-span-12 grid grid-cols-12 border-b-2 border-blue-500 mb-3">
                            <p class="col-span-4 text-left">稼働</p>
                            <p class="col-span-8 text-right">{{ number_format($month_kintai->total_working_time / 60, 2).' 時間' }}</p>
                        </div>
                        <div class="col-span-12 grid grid-cols-12 border-b-2 border-blue-500">
                            <p class="col-span-4 text-left">残業</p>
                            <p class="col-span-8 text-right">
                                @if(Gate::check('isShortTimeInfoAvailable') || $month_kintai->over_time_start == 0)
                                    {{ number_format($month_kintai->total_over_time / 60, 2).' 時間' }}
                                @else
                                    {{ number_format(0 / 60, 2).' 時間' }}
                                @endif
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
