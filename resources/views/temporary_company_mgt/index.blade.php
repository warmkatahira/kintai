<x-app-layout>
    <x-page-header content="派遣会社管理"/>
    <div class="flex flex-row mb-2">
        <!-- 操作ボタン -->
        <x-temporary-company-mgt.operation-div />
    </div>
    <div class="flex flex-row items-start mb-2">
        <!-- 従業員一覧 -->
        <x-temporary-company-mgt.list :temporaryCompanies="$temporary_companies" />
    </div>
</x-app-layout>
