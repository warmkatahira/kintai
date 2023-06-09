<x-app-layout>
    <x-page-header content="荷主グループ詳細"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap mb-2">
            <a href="{{ session('back_url_1') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
            <a href="{{ route('customer_group_update.index', ['customer_group_id' => $customer_group->customer_group_id]) }}" class="w-40 text-xl py-4 rounded-lg text-center bg-blue-200 ml-auto">更新</a>
        </div>
        <!-- 荷主グループ情報 -->
        <div class="flex flex-col">
            <p class="border-l-8 border-theme-sub text-xl pl-3 my-3">荷主グループ情報</p>
            <div class="flex flex-row">
                <x-detail-div label="拠点" :value="$customer_group->base->base_name" />
                <x-detail-div label="荷主名" :value="$customer_group->customer_group_name" />
                <x-detail-div label="荷主グループ順" :value="$customer_group->customer_group_sort_order" />
            </div>
        </div>
        <!-- 登録荷主一覧 -->
        <div class="flex flex-col">
            <p class="border-l-8 border-theme-sub text-xl pl-3 my-3">登録荷主一覧</p>
            <div class="flex flex-col">
                @foreach($customers as $customer)
                    <p class="my-0.5"><i class="las la-building mr-1 la-lg"></i>{{ $customer->customer_name }}</p>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
