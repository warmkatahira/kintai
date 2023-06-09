<x-app-layout>
    <x-page-header content="従業員管理"/>
    <div class="flex flex-row mb-2">
        <!-- 操作ボタン -->
        <x-employee-mgt.operation-div />
        <!-- ページネーション -->
        <x-pagenation :pages="$employees" />
    </div>
    <div class="flex flex-row items-start mb-2">
        <!-- 検索条件 -->
        <x-employee-mgt.search :bases="$bases" :employeeCategories="$employee_categories" searchRoute="employee_mgt.search" resetRoute="employee_mgt.index" />
        <!-- 従業員一覧 -->
        <x-employee-mgt.list :employees="$employees" />
    </div>
</x-app-layout>
