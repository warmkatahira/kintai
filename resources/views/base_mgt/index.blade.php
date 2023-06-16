<x-app-layout>
    <x-page-header content="拠点管理"/>
    <div class="flex flex-row mb-2">
        <!-- 操作ボタン -->
        <x-base-mgt.operation-div />
    </div>
    <div class="flex flex-row items-start mb-2">
        <!-- 拠点一覧 -->
        <x-base-mgt.list :bases="$bases" />
    </div>
</x-app-layout>
