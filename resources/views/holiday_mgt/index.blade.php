@vite(['resources/js/holiday_mgt/holiday_mgt.js'])

<x-app-layout>
    <x-page-header content="休日管理"/>
    <div class="flex flex-row mb-2">
        <!-- 操作ボタン -->
        <x-holiday-mgt.operation-div />
    </div>
    <div class="flex flex-row items-start mb-2">
        <!-- 休日一覧 -->
        <x-holiday-mgt.list :holidays="$holidays" />
    </div>
</x-app-layout>
