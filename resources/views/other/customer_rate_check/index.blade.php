<x-app-layout>
    <x-page-header content="荷主割合確認"/>
    <div class="flex flex-row mb-2">
        <!-- 操作ボタン -->
        <x-customer-rate-check.operation-div />
    </div>
    <div class="flex flex-row items-start mb-2">
        <!-- 検索条件 -->
        <x-customer-rate-check.search :bases="$bases" :employeeCategories="$employee_categories" searchRoute="customer_rate_check.search" resetRoute="customer_rate_check.index" />
        <!-- 荷主割合確認一覧 -->
        <x-customer-rate-check.list :employees="$employees" :customerWorkingTimes="$customer_working_times" />
    </div>
</x-app-layout>
