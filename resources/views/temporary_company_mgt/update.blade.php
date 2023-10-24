@vite(['resources/js/temporary_company_mgt/temporary_company_update.js'])

<x-app-layout>
    <x-page-header content="派遣会社情報更新"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap mb-2">
            <a href="{{ route('temporary_company_mgt.index') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
        </div>
        <!-- バリデーションエラー -->
        <x-validation-error-msg />
        <!-- 入力フォーム -->
        <div class="bg-white border border-gray-200 px-10 py-5 whitespace-nowrap">
            <form method="POST" action="{{ route('temporary_company_update.update') }}" class="m-0" id="temporary_company_update_form">
                @csrf
                <x-create-input label="派遣会社名" type="text" id="temporary_company_name" :db="$temporary_company->temporary_company_name" required="1" tippy="0" />
                <x-create-input label="時給単価" type="tel" id="hourly_rate" :db="$temporary_company->hourly_rate" required="1" tippy="0" />
                <input type="hidden" name="temporary_company_id" value="{{ $temporary_company->temporary_company_id }}">
                <button type="button" id="temporary_company_update_enter" class="border border-blue-500 text-blue-500 bg-blue-100 py-1 px-10 mt-5">更新</button>
            </form>
        </div>
    </div>
</x-app-layout>