<div class="flex flex-row">
    @can('isEmployeeOperationAvailable')
        <a href="{{ route('employee_create.index') }}" class="text-sm bg-theme-sub py-2 px-10 mr-10">従業員追加</a>
    @endcan
</div>