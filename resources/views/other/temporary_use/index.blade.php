@vite(['resources/js/other/temporary_use.js'])

<x-app-layout>
    <x-page-header content="派遣利用"/>
    <div class="flex flex-row mb-2">
        <!-- 操作ボタン -->
        <x-temporary-use.operation-div :temporaryUseTotal="$temporary_use_total" />
        <!-- ページネーション -->
        <x-pagenation :pages="$temporary_uses" />
    </div>
    <div class="flex flex-row items-start mb-2">
        <!-- 検索条件 -->
        <x-temporary-use.search :bases="$bases" :temporaryCompanies="$temporary_companies" searchRoute="temporary_use.search" resetRoute="temporary_use.index" />
        <!-- 派遣利用一覧 -->
        <x-temporary-use.list :temporaryUses="$temporary_uses" />
    </div>
</x-app-layout>