@vite(['resources/js/user_mgt/no_ip_check_bulk_update.js'])
<x-app-layout>
    <x-page-header content="ユーザー管理"/>
    <div class="flex flex-row mb-2">
        <!-- ページネーション -->
        <x-pagination :pages="$users" />
    </div>
    <div class="flex flex-row items-start mb-2">
        <div class="flex flex-col">
            <!-- 検索条件 -->
            <x-user-mgt.search :bases="$bases" searchRoute="user_mgt.search" resetRoute="user_mgt.index" />
            <!-- IPチェックなし一括更新 -->
            <x-user-mgt.no-ip-check-bulk-update :bases="$bases" />
        </div>
        <!-- ユーザー一覧 -->
        <x-user-mgt.list :users="$users" />
    </div>
</x-app-layout>
