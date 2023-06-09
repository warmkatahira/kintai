<x-app-layout>
    <x-page-header content="今日の勤怠"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap">
            <a href="{{ route('punch.index') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
        </div>
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 grid grid-cols-12 gap-4 mt-5">
                @foreach($employees as $employee)
                    <div class="col-span-3 grid grid-cols-12 text-3xl rounded-lg px-8 {{ App\Lib\GetTodayKintaiStatusFunc::Color(App\Lib\GetTodayKintaiStatusFunc::Status($employee->employee_id)) }}">
                        <p class="col-span-12 text-left mt-3">{{ App\Lib\GetTodayKintaiStatusFunc::Status($employee->employee_id) }}</p>
                        <p class="col-span-12 text-center py-5">{{ $employee->employee_last_name.$employee->employee_first_name }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
