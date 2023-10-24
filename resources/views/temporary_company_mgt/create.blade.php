@vite(['resources/js/temporary_company_mgt/temporary_company_create.js'])

<x-app-layout>
    <x-page-header content="派遣会社情報追加"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap mb-2">
            <a href="{{ route('temporary_company_mgt.index') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
        </div>
        <!-- バリデーションエラー -->
        <x-validation-error-msg />
        <!-- 入力フォーム -->
        <div class="bg-white border border-gray-200 px-10 py-5 whitespace-nowrap">
            <form method="POST" action="{{ route('temporary_company_create.create') }}" class="m-0" id="temporary_company_create_form">
                @csrf
                <x-create-input label="派遣会社名" type="text" id="temporary_company_name" :db="null" required="1" tippy="0" />
                <x-create-input label="時給単価" type="tel" id="hourly_rate" :db="null" required="1" tippy="0" />
                <button type="button" id="temporary_company_create_enter" class="border border-blue-500 text-blue-500 bg-blue-100 py-1 px-10 mt-5">追加</button>
            </form>
        </div>
    </div>
</x-app-layout>