@vite(['resources/js/access_mgt/ip_limit_update.js'])

<x-app-layout>
    <x-page-header content="IP更新"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap mb-2">
            <a href="{{ route('access_mgt.index') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
        </div>
        <!-- バリデーションエラー -->
        <x-validation-error-msg />
        <!-- 入力フォーム -->
        <div class="bg-white border border-gray-200 px-10 py-5 whitespace-nowrap">
            <form method="POST" action="{{ route('ip_limit_update.update') }}" class="m-0" id="ip_limit_update_form">
                @csrf
                <x-create-input label="IP" type="text" id="ip" :db="$ip_limit->ip" required="1" tippy="0" />
                <x-create-select label="拠点" id="base_id" :forValue="$bases" text="base_name" :db="$ip_limit->base_id" required="1" tippy="0" />
                <x-create-input label="備考" type="text" id="note" :db="$ip_limit->note" required="1" tippy="0" />
                <x-access-mgt.create-select label="有効/無効" id="is_available" :db="$ip_limit->is_available" required="1" tippy="0" />
                <input type="hidden" name="ip_limit_id" value="{{ $ip_limit->ip_limit_id }}">
                <button type="button" id="ip_limit_update_enter" class="border border-blue-500 text-blue-500 bg-blue-100 py-1 px-10 mt-5">更新</button>
            </form>
        </div>
    </div>
</x-app-layout>