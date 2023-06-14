<x-app-layout>
    <x-page-header content="残業ランキング"/>
    <div class="flex flex-row items-start mb-2">
        <!-- 検索条件 -->
        <x-over-time-rank.search :bases="$bases" :employeeCategories="$employee_categories" searchRoute="over_time_rank.search" resetRoute="over_time_rank.index" />
        <!-- 残業ランキング一覧 -->
        <x-over-time-rank.list :employees="$employees" />
    </div>
</x-app-layout>
