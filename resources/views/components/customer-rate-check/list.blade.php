<div class="scroll_wrap flex flex-grow overflow-scroll">
    <div class="table_parent_div bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <div class="grid grid-cols-12 gap-4 p-2 text-sm">
            @foreach($employees as $employee)
                <div class="col-span-3">
                    <div class="flex border border-black -mb-px">
                        <p class="flex-1 text-center bg-gray-300 py-1"><i class="las la-user la-lg mr-1"></i>{{ $employee->employee_last_name .' '. $employee->employee_first_name }}</p>
                        <p class="flex-1 text-center bg-gray-300 py-1"><i class="las la-business-time la-lg mr-1"></i>{{ number_format(($customerWorkingTimes[$employee->employee_id]['total'] / 60), 2).' 時間' }}</p>
                    </div>
                    <div class="col-span-12 grid grid-cols-12">
                        <!-- 3つまで表示させる -->
                        @for($i = 0; $i < 3; $i++)
                            @if(isset($customerWorkingTimes[$employee->employee_id]['rank'][$i]))
                                <div class="col-span-12 bg-pink-200 py-2 border border-black -mb-px">
                                    <p class="text-center">{{ $customerWorkingTimes[$employee->employee_id]['rank'][$i]->customer_name }}</p>
                                    <p class="text-center">
                                        {{ number_format(($customerWorkingTimes[$employee->employee_id]['rank'][$i]->total_customer_working_time / $customerWorkingTimes[$employee->employee_id]['total']) * 100, 1).' %' }}
                                        {{ '('.number_format(($customerWorkingTimes[$employee->employee_id]['rank'][$i]->total_customer_working_time / 60), 2).' 時間)' }}
                                    </p>
                                </div>
                            @endif
                        @endfor
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>