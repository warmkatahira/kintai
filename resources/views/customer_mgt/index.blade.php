<x-app-layout>
    <x-page-header content="荷主管理"/>
    <div class="flex flex-row mb-2">
        <!-- 操作ボタン -->
        <x-customer-mgt.operation-div />
        <!-- ページネーション -->
        <x-pagination :pages="$customers" />
    </div>
    <div class="flex flex-row items-start mb-2">
        <!-- 検索条件 -->
        <x-customer-mgt.search :bases="$bases" :statuses="$statuses" searchRoute="customer_mgt.search" resetRoute="customer_mgt.index" />
        <!-- 荷主一覧 -->
        <x-customer-mgt.list :customers="$customers" />
    </div>
</x-app-layout>
