<div class="grid grid-cols-12 gap-4 mt-10">
    <!-- 従業員名ボタンを表示 -->
    @foreach($employees as $employee)
        <button type="button" class="col-span-3 punch_enter bg-theme-sub text-center text-3xl rounded-lg py-10" value="{{ $employee->$key }}">{{ $employee->employee_last_name.' '.$employee->employee_first_name }}</button>
    @endforeach
    <!-- ID送信用 -->
    <input type="hidden" id="punch_id" name="punch_id">
</div>