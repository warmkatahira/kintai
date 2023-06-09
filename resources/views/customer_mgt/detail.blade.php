<x-app-layout>
    <x-page-header content="荷主詳細"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap mb-2">
            <a href="{{ session('back_url_1') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
            <a href="{{ route('customer_update.index', ['customer_id' => $customer->customer_id]) }}" class="w-40 text-xl py-4 rounded-lg text-center bg-blue-200 ml-auto">更新</a>
        </div>
        <!-- 荷主情報 -->
        <div class="flex flex-col">
            <p class="border-l-8 border-theme-sub text-xl pl-3 my-3">荷主情報</p>
            <div class="flex flex-row">
                <x-detail-div label="拠点" :value="$customer->base->base_name" />
                <x-detail-div label="荷主名" :value="$customer->customer_name" />
                <x-detail-div label="荷主グループ名" :value="!is_null($customer->customer_group) ? $customer->customer_group->customer_group_name : ''" />
            </div>
        </div>
        <!-- 当月稼働情報 -->
        <div class="flex flex-col">
            <p class="border-l-8 border-theme-sub text-xl pl-3 my-3">当月稼働情報</p>
            <div class="flex flex-row">
                <x-detail-div label="総稼働時間" :value="!is_null($kintai) ? number_format($kintai->total_customer_working_time / 60, 2) : '0.00'" />
            </div>
        </div>
        <!-- 当月稼働時間トップ3 -->
        <div class="flex flex-col w-4/12">
            <p class="border-l-8 border-theme-sub text-xl pl-3 my-3">当月稼働時間トップ5</p>
            @foreach($customer_working_time as $index => $data)
                <div class="flex flex-row border-b-4 border-theme-sub">
                    <p class=""><span class="mr-5">{{ ($index + 1).'位' }}</span>{{ $data->employee_last_name.' '.$data->employee_first_name }}</p>
                    <p class="ml-auto text-right">{{ number_format(($data->total_customer_working_time / 60), 2).' 時間（'.number_format((($data->total_customer_working_time / $kintai->total_customer_working_time) * 100),2).' %）' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
