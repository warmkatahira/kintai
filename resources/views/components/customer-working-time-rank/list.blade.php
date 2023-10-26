<!-- 荷主稼働ランキング一覧 -->
<div class="scroll_wrap flex flex-grow overflow-scroll">
    <div class="table_parent_div bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-sm block whitespace-nowrap">
            <thead>
                <tr class="text-left text-white bg-gray-600 sticky top-0">
                    <th class="font-thin py-3 px-2 text-center">詳細</th>
                    <th class="font-thin py-3 px-2 text-center">Rank</th>
                    <th class="font-thin py-3 px-2 text-center">年月</th>
                    <th class="font-thin py-3 px-2 text-center">拠点</th>
                    <th class="font-thin py-3 px-2 text-center">荷主名</th>
                    <th class="font-thin py-3 px-2 text-center">稼働時間（社員）<br><span class="text-xs">(合計に対する比率)</span></th>
                    <th class="font-thin py-3 px-2 text-center">稼働時間（パート）<br><span class="text-xs">(合計に対する比率)</span></th>
                    <th class="font-thin py-3 px-2 text-center">稼働時間（派遣）<br><span class="text-xs">(合計に対する比率)</span></th>
                    <th class="font-thin py-3 px-2 text-center">稼働時間（合計）</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($customers as $key => $customer)
                    <tr class="text-left hover:bg-theme-sub cursor-default">
                        <td class="py-1 px-2 border text-center">
                            <a href="{{ route('customer_working_time_rank.detail', ['customer_id' => $customer->customer_id, 'date' => $customer->date, 'total' => $customer->total_customer_working_time_total, 'shain' => $customer->total_customer_working_time_shain, 'part' => $customer->total_customer_working_time_part]) }}" class="text-center border border-blue-500 bg-blue-100 text-blue-500 text-xs px-2 py-1">詳細</a>
                        </td>
                        <td class="py-1 px-2 border text-center">{{ sprintf('%03d', $customers->firstItem() + $loop->index) }}</td>
                        <td class="py-1 px-2 border text-center">{{ \Carbon\CarbonImmutable::parse($customer->date)->isoFormat('YYYY年MM月') }}</td>
                        <td class="py-1 px-2 border">{{ $customer->base->base_name }}</td>
                        <td class="py-1 px-2 border text-left">{{ $customer->customer_name }}</td>
                        <td class="py-1 px-2 border text-right">
                            {{ number_format($customer->total_customer_working_time_shain / 60, 2) }}<br>
                            {{ '('.number_format((($customer->total_customer_working_time_shain / $customer->total_customer_working_time_total) * 100), 1).' %)' }}
                        </td>
                        <td class="py-1 px-2 border text-right">
                            {{ number_format($customer->total_customer_working_time_part / 60, 2) }}<br>
                            {{ '('.number_format((($customer->total_customer_working_time_part / $customer->total_customer_working_time_total) * 100), 1).' %)' }}
                        </td>
                        <td class="py-1 px-2 border text-right">
                            {{ number_format($customer->total_customer_working_time_temporary / 60, 2) }}<br>
                            {{ '('.number_format((($customer->total_customer_working_time_temporary / $customer->total_customer_working_time_total) * 100), 1).' %)' }}
                        </td>
                        <td class="py-1 px-2 border text-right">{{ number_format($customer->total_customer_working_time_total / 60, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>