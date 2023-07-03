<x-app-layout>
    <x-page-header content="荷主詳細"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap mb-2">
            <a href="{{ session('back_url_1') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
            <!-- 自拠点かつ荷主管理機能が有効な場合 -->
            @if(Auth::user()->base_id == $customer->base_id && Gate::check('isCustomerMgtFuncAvailable'))
                <a href="{{ route('customer_update.index', ['customer_id' => $customer->customer_id]) }}" class="w-40 text-xl py-4 rounded-lg text-center bg-blue-200 ml-auto">更新</a>
            @endif
        </div>
        <!-- 有効/無効 -->
        <div class="flex flex-col">
            <p class="border-l-8 border-theme-sub text-xl pl-3 my-3">有効/無効</p>
            <div class="flex flex-row">
                <x-detail-div label="有効/無効" :value="$customer->is_available == 1 ? '有効' : '無効'" class="w-40 text-center" />
            </div>
        </div>
        <!-- 荷主情報 -->
        <div class="flex flex-col">
            <p class="border-l-8 border-theme-sub text-xl pl-3 my-3">荷主情報</p>
            <div class="flex flex-row">
                <x-detail-div label="拠点" :value="$customer->base->base_name" class="w-40 text-left pl-3" />
            </div>
            <div class="flex flex-row">
                <x-detail-div label="荷主名" :value="$customer->customer_name" class="w-80 text-left pl-3" />
            </div>
            <div class="flex flex-row">
                <x-detail-div label="荷主グループ名" :value="!is_null($customer->customer_group) ? $customer->customer_group->customer_group_name : ''" class="w-40 text-left pl-3" />
            </div>
            <div class="flex flex-row">
                <x-detail-div label="荷主並び順" :value="$customer->customer_sort_order" class="w-40 text-left pl-3" />
            </div>
        </div>
    </div>
</x-app-layout>
