<div class="bg-white border border-gray-600 shadow-md mr-3">
    <p class="text-sm bg-gray-600 text-white py-3 text-center">検索条件</p>
    <form method="GET" action="{{ route($searchRoute) }}" class="m-0">
        <div class="flex flex-col px-3">
            <x-search.select-1 id="search_base_id" label="拠点" :searchConditions="$bases" value="base_id" text="base_name" />
            <x-search.select-4 id="search_available" label="有効/無効" :searchConditions="$statuses" value="status" text="status_name" />
            <x-search.select-1 id="search_employee_category_id" label="従業員区分" :searchConditions="$employeeCategories" value="employee_category_id" text="employee_category_name" />
            <x-search.input id="search_employee_name" label="従業員名" />
            <x-search.select-3 id="search_sort_order" label="並び順" :searchConditions="$sortOrderConditions" />
            <button type="submit" class="text-sm text-center border border-blue-500 text-blue-500 bg-blue-100 py-2 mt-2 shadow-md">
                <i class="las la-search la-lg"></i>検索
            </button>
            <a href="{{ route($resetRoute) }}" class="text-sm text-center border border-black bg-gray-100 py-2 my-2 shadow-md">
                <i class="las la-eraser la-lg"></i>リセット
            </a>
        </div>
    </form>
</div>