@vite(['resources/js/employee_mgt/employee_update.js'])

<x-app-layout>
    <x-page-header content="従業員情報更新"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap mb-2">
            <a href="{{ session('back_url_2') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
        </div>
        <!-- バリデーションエラー -->
        <x-validation-error-msg />
        <!-- 入力フォーム -->
        <div class="bg-white border border-gray-200 px-10 py-5 whitespace-nowrap">
            <form method="POST" action="{{ route('employee_update.update') }}" class="m-0" id="employee_update_form">
                @csrf
                <x-create-select label="拠点" id="base_id" :forValue="$bases" text="base_name" :db="$employee->base_id" required="1" tippy="0" />
                <x-create-select label="従業員区分" id="employee_category_id" :forValue="$employee_categories" text="employee_category_name" :db="$employee->employee_category_id" required="1" tippy="0" />
                <x-create-input label="従業員番号" type="text" id="employee_no" :db="$employee->employee_no" required="1" tippy="0" />
                <x-create-input label="従業員名(姓)" type="text" id="employee_last_name" :db="$employee->employee_last_name" required="1" tippy="0" />
                <x-create-input label="従業員名(名)" type="text" id="employee_first_name" :db="$employee->employee_first_name" required="1" tippy="0" />
                <x-create-input label="月間稼働可能時間" type="text" id="monthly_workable_time" :db="$employee->monthly_workable_time" required="1" tippy="tippy_monthly_workable_time" />
                <x-create-input label="残業開始時間" type="text" id="over_time_start" :db="$employee->over_time_start" required="1" tippy="tippy_over_time_start" />
                <input type="hidden" name="employee_id" value="{{ $employee->employee_id }}">
                <button type="button" id="employee_update_enter" class="border border-blue-500 text-blue-500 bg-blue-100 py-1 px-10 mt-5">更新</button>
            </form>
        </div>
    </div>
</x-app-layout>