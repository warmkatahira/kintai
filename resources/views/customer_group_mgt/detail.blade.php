<x-app-layout>
    <x-page-header content="荷主グループ詳細"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap mb-2">
            <a href="{{ session('back_url_1') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
            <!-- 自拠点かつ荷主管理機能が有効な場合 -->
            @if(Auth::user()->base_id == $customer_group->base_id && Gate::check('isCustomerMgtFuncAvailable'))
                <a href="{{ route('customer_group_update.index', ['customer_group_id' => $customer_group->customer_group_id]) }}" class="w-40 text-xl py-4 rounded-lg text-center bg-blue-200 ml-auto">更新</a>
            @endif
        </div>
        <!-- 荷主グループ情報 -->
        <div class="flex flex-col">
            <p class="border-l-8 border-theme-sub text-xl pl-3 my-3">荷主グループ情報</p>
            <div class="flex flex-row">
                <x-detail-div label="拠点" :value="$customer_group->base->base_name" class="w-40 text-left pl-3" />
            </div>
            <div class="flex flex-row">
                <x-detail-div label="荷主名" :value="$customer_group->customer_group_name" class="w-80 text-left pl-3" />
            </div>
            <div class="flex flex-row">
                <x-detail-div label="荷主グループ並び順" :value="$customer_group->customer_group_sort_order" class="w-40 text-left pl-3" />
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
