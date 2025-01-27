@vite(['resources/js/access_mgt/ip_limit_delete.js'])

<x-app-layout>
    <x-page-header content="アクセス管理"/>
    <div class="flex flex-row mb-2">
        <!-- 操作ボタン -->
        <x-access-mgt.operation-div />
    </div>
    <!-- 拠点毎の登録数 -->
    <x-access-mgt.ip-limit-by-base :ipLimitByBase="$ip_limit_by_base" />
    <div class="flex flex-row items-start mb-2">
        <!-- IP一覧 -->
        <x-access-mgt.list :ipLimits="$ip_limits" />
    </div>
</x-app-layout>
