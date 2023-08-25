@vite(['resources/js/punch/punch_complete_popup.js', 'resources/css/punch_complete_popup.css'])

<div class="mx-5 mb-2">
    @if(session('punch_type'))
        <div class="punch_finish grid grid-cols-12 p-5">
            <div class="col-span-12 grid grid-cols-12 gap-x-4 bg-gray-200 border-8 border-dotted border-blue-500 px-5">
                @if(session('punch_type') == '退勤')
                    <lord-icon src="https://cdn.lordicon.com/uutnmngi.json" trigger="loop" style="width:120px;height:120px" class="col-span-1 mx-auto block"></lord-icon>
                    <p class="text-5xl text-center col-start-2 col-span-8 mt-7 text-orange-600">{{ session('employee_name') }}<span class="text-5xl"> さん</span></p>
                    <p class="text-5xl text-center col-start-10 col-span-3 mt-7 text-orange-600"></lord-icon>{{ session('punch_type') }}</p>
                    <div class="text-5xl text-center col-start-1 col-span-4 row-span-1 text-white bg-blue-600 rounded-lg py-10">
                        <p class="">{{ '月間稼働設定' }}</p>
                        <p class="mt-10">{{ number_format(session('monthly_workable_time'), 2) }}</p>
                    </div>
                    <div class="text-5xl text-center col-start-5 col-span-4 row-span-1 text-white bg-blue-600 rounded-lg py-10">
                        <p class="">{{ '総稼働' }}</p>
                        <p class="mt-10">{{ number_format(session('total_month_working_time'), 2) }}</p>
                    </div>
                    <div class="text-5xl text-center col-start-9 col-span-4 row-span-1 text-white bg-blue-600 rounded-lg py-10">
                        <p class="">{{ '稼働可能残' }}</p>
                        <p class="mt-10">{{ number_format(session('workable_times'), 2) }}</p>
                    </div>
                @else
                    <lord-icon src="https://cdn.lordicon.com/uutnmngi.json" trigger="loop" style="width:120px;height:120px" class="col-span-12 mx-auto block"></lord-icon>
                    <p class="text-7xl text-center col-start-4 col-span-6 row-span-1 mt-7 text-orange-600">{{ session('punch_type') }}</p>
                    <p class="text-7xl text-center col-start-4 col-span-6 row-span-1 mt-7 text-orange-600">{{ session('employee_name') }}<span class="text-5xl"> さん</span></p>
                @endif
                @if(session('punch_type') == '出勤' && session('no_finish_kintai_count') > 0)
                    <p class="text-5xl text-center col-start-2 col-span-10 row-span-1 mt-7 text-red-600 border-8 border-dotted border-red-600 p-3">退勤漏れがあります。所長へ報告して下さい。</p>
                @endif
                <p class="col-start-1 col-span-12 text-center text-7xl tracking-in-expand mt-7">{{ session('message') }}</p>
            </div>
        </div>
    @endif
</div>