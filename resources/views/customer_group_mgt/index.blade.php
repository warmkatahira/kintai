<x-app-layout>
    <x-page-header content="荷主グループ管理"/>
    <div class="flex flex-row mb-2">
        <!-- 操作ボタン -->
        <x-customer-group-mgt.operation-div />
        <!-- ページネーション -->
        <x-pagination :pages="$customer_groups" />
    </div>
    <div class="flex flex-row items-start mb-2">
        <!-- 検索条件 -->
        <x-customer-group-mgt.search :bases="$bases" searchRoute="customer_group_mgt.search" resetRoute="customer_group_mgt.index" />
        <!-- 荷主グループ一覧 -->
        <x-customer-group-mgt.list :customerGroups="$customer_groups" />
    </div>
</x-app-layout>
