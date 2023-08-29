@vite(['resources/js/customer_mgt/customer_create.js'])

<x-app-layout>
    <x-page-header content="荷主追加"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap mb-2">
            <a href="{{ session('back_url_1') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
        </div>
        <!-- バリデーションエラー -->
        <x-validation-error-msg />
        <!-- 入力フォーム -->
        <div class="bg-white border border-gray-200 px-10 py-5 whitespace-nowrap">
            <form method="POST" action="{{ route('customer_create.create') }}" class="m-0" id="customer_create_form">
                @csrf
                <x-create-select label="拠点" id="base_id" :forValue="$bases" text="base_name" :db="Auth::user()->base_id" required="1" tippy="0" />
                <x-create-input label="荷主名" type="text" id="customer_name" :db="null" required="1" tippy="0" />
                <x-create-select label="荷主グループ" id="customer_group_id" :forValue="$customer_groups" text="customer_group_name" :db="null" required="0" tippy="0" />
                <x-create-input label="荷主並び順" type="text" id="customer_sort_order" :db="null" required="1" tippy="0" />
                <button type="button" id="customer_create_enter" class="border border-blue-500 text-blue-500 bg-blue-100 py-1 px-10 mt-5">追加</button>
            </form>
        </div>
    </div>
</x-app-layout>