<!-- 休日一覧 -->
<div class="scroll_wrap flex flex-grow overflow-scroll">
    <div class="table_parent_div bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-sm block whitespace-nowrap">
            <thead>
                <tr class="text-left text-white bg-gray-600 sticky top-0">
                    <th class="font-thin py-3 px-2 text-center">休日</th>
                    <th class="font-thin py-3 px-2 text-center">備考</th>
                    <th class="font-thin py-3 px-2 text-center">国民の祝日</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($holidays as $holiday)
                    <tr class="text-left hover:bg-theme-sub cursor-default">
                        <td class="py-1 px-2 border text-center">{{ \Carbon\CarbonImmutable::parse($holiday->holiday)->isoFormat('YYYY年MM月DD日(ddd)') }}</td>
                        <td class="py-1 px-2 border text-center">{{ $holiday->note }}</td>
                        <td class="py-1 px-2 border text-center">@if($holiday->is_national_holiday == 1) ○ @endif</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>