<x-app-layout>
    <x-page-header content="荷主稼働ランキング"/>
    <div class="flex flex-row mb-2">
        <!-- ページネーション -->
        <x-pagenation :pages="$customers" />
    </div>
    <div class="flex flex-row items-start mb-2">
        <!-- 検索条件 -->
        <x-customer-working-time-rank.search :bases="$bases" searchRoute="customer_working_time_rank.search" resetRoute="customer_working_time_rank.index" />
        <!-- 残業ランキング一覧 -->
        <x-customer-working-time-rank.list :customers="$customers" />
    </div>
</x-app-layout>
