<x-app-layout>
    <x-page-header content="荷主稼働ランキング詳細"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap mb-2">
            <a href="{{ session('back_url_1') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
        </div>
        <!-- 概要 -->
        <div class="flex flex-col">
            <p class="border-l-8 border-theme-sub text-xl pl-3 my-3">概要</p>
            <div class="flex flex-row">
                <x-detail-div label="年月" :value="\Carbon\CarbonImmutable::parse($date)->isoFormat('YYYY年MM月')" class="w-40 text-left pl-3" />
            </div>
            <div class="flex flex-row">
                <x-detail-div label="拠点" :value="$customer->base->base_name" class="w-40 text-left pl-3" />
            </div>
            <div class="flex flex-row">
                <x-detail-div label="荷主名" :value="$customer->customer_name" class="w-80 text-left pl-3" />
            </div>
        </div>
        <!-- 稼働時間 -->
        <div class="flex flex-col">
            <p class="border-l-8 border-theme-sub text-xl pl-3 my-3">稼働時間</p>
            <div class="flex flex-row">
                <x-detail-div label="社員" :value="number_format(($shain / 60), 2).' 時間'" class="w-40 text-right pr-3" />
            </div>
            <div class="flex flex-row">
                <x-detail-div label="パート" :value="number_format(($part / 60), 2).' 時間'" class="w-40 text-right pr-3" />
            </div>
            <div class="flex flex-row">
                <x-detail-div label="合計" :value="number_format(($total / 60), 2).' 時間'" class="w-40 text-right pr-3" />
            </div>
        </div>
        <!-- 稼働時間トップ10 -->
        <div class="flex flex-col w-4/12">
            <p class="border-l-8 border-theme-sub text-xl pl-3 my-3">稼働時間トップ10</p>
            @foreach($employees as $index => $employee)
                <div class="flex flex-row border-b-4 border-theme-sub pt-1">
                    <p class=""><span class="mr-5">{{ ($index + 1).'位' }}</span>{{ $employee->employee_last_name.' '.$employee->employee_first_name }}</p>
                    <p class="ml-auto text-right">{{ number_format(($employee->total_customer_working_time / 60), 2).' 時間（'.number_format((($employee->total_customer_working_time / $total) * 100),2).' %）' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
