<x-app-layout>
    <x-page-header content="権限管理"/>
    <div class="flex flex-row mb-2">
        <!-- 操作ボタン -->
        <x-role-mgt.operation-div />
    </div>
    <div class="flex flex-row items-start mb-2">
        <!-- 権限一覧 -->
        <x-role-mgt.list :roles="$roles" />
    </div>
</x-app-layout>
