@vite(['resources/js/base_mgt/base_update.js'])

<x-app-layout>
    <x-page-header content="拠点更新"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap mb-2">
            <a href="{{ route('base_mgt.index') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
        </div>
        <!-- バリデーションエラー -->
        <x-validation-error-msg />
        <!-- 入力フォーム -->
        <div class="bg-white border border-gray-200 px-10 py-5 whitespace-nowrap">
            <form method="POST" action="{{ route('base_update.update') }}" class="m-0" id="base_update_form">
                @csrf
                <div class="flex text-sm bg-gray-600 py-1 text-white pl-5">
                    <p>拠点ID</p>
                </div>
                <div class="border px-10 py-5">
                    <p class="w-96 text-sm py-2 px-3">{{ $base->base_id }}</p>
                </div>
                <x-create-input label="拠点名" type="text" id="base_name" :db="$base->base_name" required="1" tippy="0" />
                <x-base-mgt.create-select label="追加休憩取得" id="is_add_rest_available" :db="$base->is_add_rest_available" required="1" tippy="0" />
                <input type="hidden" name="base_id" value="{{ $base->base_id }}">
                <button type="button" id="base_update_enter" class="border border-blue-500 text-blue-500 bg-blue-100 py-1 px-10 mt-5">更新</button>
            </form>
        </div>
    </div>
</x-app-layout>