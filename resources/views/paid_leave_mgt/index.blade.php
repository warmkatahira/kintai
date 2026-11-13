@vite(['resources/js/paid_leave_mgt/paid_leave_mgt.js'])

<x-app-layout>
    <x-page-header content="有給管理"/>
    <div class="flex flex-row mb-2">
        <!-- 操作ボタン -->
        <x-paid-leave-mgt.operation-div />
        <!-- ページネーション -->
        <x-pagination :pages="$paid_leaves" />
    </div>
    <div class="flex flex-row items-start mb-2">
        <!-- 検索条件 -->
        <x-paid-leave-mgt.search :bases="$bases" />
        <!-- 有給一覧 -->
        <x-paid-leave-mgt.list :paidLeaves="$paid_leaves" />
    </div>
</x-app-layout>
