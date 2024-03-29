<x-app-layout>
    <x-page-header content="ユーザー管理"/>
    <div class="flex flex-row mb-2">
        <!-- ページネーション -->
        <x-pagination :pages="$users" />
    </div>
    <div class="flex flex-row items-start mb-2">
        <!-- 検索条件 -->
        <x-user-mgt.search :bases="$bases" searchRoute="user_mgt.search" resetRoute="user_mgt.index" />
        <!-- ユーザー一覧 -->
        <x-user-mgt.list :users="$users" />
    </div>
</x-app-layout>
