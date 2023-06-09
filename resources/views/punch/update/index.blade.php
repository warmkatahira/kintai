<x-app-layout>
    <x-page-header content="修正打刻"/>
    <div class="my-5">
        <!-- バリデーションエラー表示 -->
        <x-validation-error-msg />
        <form method="GET" action="{{ route('punch_update.input') }}" class="m-0 py-5">
            <x-punch.punch-begin-type :db="$kintai->is_early_worked" />
            <div class="flex flex-col mt-10">
                <div class="flex flex-row mt-1">
                    <label for="work_day" class="w-52 bg-black text-white text-center text-sm py-2">出勤日</label>
                    <input type="date" id="work_day" name="work_day" class="w-40 text-sm" autocomplete="off" value="{{ $kintai->work_day }}" readonly>
                </div>
                <div class="flex flex-row mt-1">
                    <label for="employee_id" class="w-52 bg-black text-white text-center text-sm py-2">従業員</label>
                    <input type="text" class="w-96 text-sm" value="{{ $kintai->employee->employee_last_name.$kintai->employee->employee_first_name }}" readonly>
                    <input type="hidden" id="employee_id" name="employee_id" value="{{ $kintai->employee_id }}">
                </div>
                <div class="flex flex-row mt-1">
                    <label for="begin_time" class="w-52 bg-black text-white text-center text-sm py-2">出勤時間</label>
                    <input type="time" id="begin_time" name="begin_time" class="w-40 text-sm" autocomplete="off" value="{{ old('begin_time', $kintai->begin_time) }}">
                </div>
                <div class="flex flex-row mt-1">
                    <label for="finish_time" class="w-52 bg-black text-white text-center text-sm py-2">退勤時間</label>
                    <input type="time" id="finish_time" name="finish_time" class="w-40 text-sm" autocomplete="off" value="{{ old('finish_time', $kintai->finish_time) }}">
                </div>
                <div class="flex flex-row mt-1">
                    <label for="out_time" class="w-52 bg-black text-white text-center text-sm py-2">外出時間</label>
                    <input type="time" id="out_time" name="out_time" class="w-40 text-sm" autocomplete="off" value="{{ old('out_time', $kintai->out_time) }}">
                </div>
                <div class="flex flex-row mt-1">
                    <label for="return_time" class="w-52 bg-black text-white text-center text-sm py-2">戻り時間</label>
                    <input type="time" id="return_time" name="return_time" class="w-40 text-sm" autocomplete="off" value="{{ old('return_time', $kintai->return_time) }}">
                </div>
                <button type="submit" class="w-40 bg-blue-200 text-center rounded-lg mt-5 py-3">次へ</button>
            </div>
        </form>
    </div>
</x-app-layout>
