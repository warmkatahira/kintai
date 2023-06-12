<x-app-layout>
    <x-page-header content="勤怠提出確認"/>
    <div class="flex flex-row items-start mb-2">
        <!-- 検索条件 -->
        <x-kintai-close-check.search searchRoute="kintai_close_check.search" resetRoute="kintai_close_check.index" />
        <!-- 勤怠提出情報一覧 -->
        <x-kintai-close-check.list :kintaiCloses="$kintai_closes" />
    </div>
</x-app-layout>
