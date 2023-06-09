@vite(['resources/js/kintai_mgt/kintai_mgt.js'])

<x-app-layout>
    <x-page-header content="勤怠管理"/>
    <div class="flex flex-row mb-2">
        <!-- 操作ボタン -->
        <x-kintai-mgt.operation-div />
        <!-- ページネーション -->
        <x-pagenation :pages="$kintais" />
    </div>
    <div class="flex flex-row items-start mb-2">
        <!-- 検索条件 -->
        <x-kintai-mgt.search :targetConditions="$target_conditions" :baseCheckConditions="$base_check_conditions" :bases="$bases" :employeeCategories="$employee_categories" searchRoute="kintai_mgt.search" resetRoute="kintai_mgt.index" />
        <!-- 勤怠一覧 -->
        <x-kintai-mgt.list :kintais="$kintais" :addRestAvailable="$add_rest_available" />
    </div>
</x-app-layout>
