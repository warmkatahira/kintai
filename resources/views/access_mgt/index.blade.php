<x-app-layout>
    <x-page-header content="アクセス管理"/>
    <div class="flex flex-row mb-2">
        <!-- 操作ボタン -->
        <x-access-mgt.operation-div />
    </div>
    <div class="flex flex-row items-start mb-2">
        <!-- IP一覧 -->
        <x-access-mgt.list :ipLimits="$ip_limits" />
    </div>
</x-app-layout>
