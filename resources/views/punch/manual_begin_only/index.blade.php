@vite(['resources/js/punch/punch_manual_begin_only.js'])

<x-app-layout>
    <x-page-header content="手動打刻(出勤のみ)"/>
    <div class="my-5">
        <!-- バリデーションエラー表示 -->
        <x-validation-error-msg />
        <form method="POST" action="{{ route('punch_manual_begin_only.enter') }}" id="punch_manual_begin_only_form" class="m-0 py-5">
            @csrf
            <x-punch.punch-begin-type :db="0" />
            <div class="flex flex-col mt-10">
                <div class="flex flex-row mt-1">
                    <label for="work_day" class="w-52 bg-black text-white text-center text-sm py-2">出勤日</label>
                    <input type="date" id="work_day" name="work_day" class="w-40 text-sm" autocomplete="off" value="{{ old('work_day') }}">
                </div>
                <div class="flex flex-row mt-1">
                    <label for="employee_id" class="w-52 bg-black text-white text-center text-sm py-2">従業員</label>
                    <select id="employee_id" name="employee_id" class="w-40 text-sm">
                        @foreach($employees as $employee)
                            <option value="{{ $employee->employee_id }}" @if($employee->employee_id == old('employee_id')) selected @endif>{{ $employee->employee_last_name.$employee->employee_first_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-row mt-1">
                    <label for="begin_time" class="w-52 bg-black text-white text-center text-sm py-2">出勤時間</label>
                    <input type="time" id="begin_time" name="begin_time" class="w-40 text-sm" autocomplete="off" value="{{ old('begin_time') }}">
                </div>
                <button type="button" id="punch_enter" class="w-40 bg-blue-200 text-center rounded-lg mt-5 py-3">打刻</button>
            </div>
        </form>
    </div>
</x-app-layout>
