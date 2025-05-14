<div class="bg-white border border-gray-600 shadow-md mr-3">
    <p class="text-sm bg-gray-600 text-white py-3 text-center">検索条件</p>
    <form method="GET" action="{{ route($searchRoute) }}" class="m-0">
        <div class="flex flex-col px-3">
            <x-search.date-period idFrom="search_work_day_from" idTo="search_work_day_to" label="出勤日" />
            <x-search.select-1 id="search_base_id" label="拠点" :searchConditions="$bases" value="base_id" text="base_name" />
            <x-search.select-1 id="search_employee_category_id" label="従業員区分" :searchConditions="$employeeCategories" value="employee_category_id" text="employee_category_name" />
            <x-search.input id="search_employee_name" label="従業員名" />
            <x-search.select-2 id="search_target" label="対象" :searchConditions="$targetConditions" />
            <x-search.select-2 id="search_base_check" label="拠点確認" :searchConditions="$baseCheckConditions" />
            <x-search.select-2 id="search_chief_approvaled" label="所長承認" :searchConditions="$chiefApprovaledConditions" />
            <x-search.select-2 id="search_law_violated" label="法令違反" :searchConditions="$lawViolatedConditions" />
            <button type="submit" class="text-sm text-center border border-blue-500 text-blue-500 bg-blue-100 py-2 mt-2 shadow-md">
                <i class="las la-search la-lg"></i>検索
            </button>
            <a href="{{ route($resetRoute) }}" class="text-sm text-center border border-black bg-gray-100 py-2 my-2 shadow-md">
                <i class="las la-eraser la-lg"></i>リセット
            </a>
        </div>
    </form>
</div>