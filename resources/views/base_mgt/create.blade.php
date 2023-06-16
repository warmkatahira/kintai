@vite(['resources/js/base_mgt/base_create.js'])

<x-app-layout>
    <x-page-header content="拠点追加"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap mb-2">
            <a href="{{ route('base_mgt.index') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
        </div>
        <!-- バリデーションエラー -->
        <x-validation-error-msg />
        <!-- 入力フォーム -->
        <div class="bg-white border border-gray-200 px-10 py-5 whitespace-nowrap">
            <form method="POST" action="{{ route('base_create.create') }}" class="m-0" id="base_create_form">
                @csrf
                <x-create-input label="拠点ID" type="text" id="base_id" :db="null" required="1" tippy="0" />
                <x-create-input label="拠点名" type="text" id="base_name" :db="null" required="1" tippy="0" />
                <x-base-mgt.create-select label="有効/無効" id="is_add_rest_available" :db="null" required="1" tippy="0" />
                <button type="button" id="base_create_enter" class="border border-blue-500 text-blue-500 bg-blue-100 py-1 px-10 mt-5">追加</button>
            </form>
        </div>
    </div>
</x-app-layout>