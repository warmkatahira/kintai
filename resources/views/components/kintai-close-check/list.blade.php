<!-- 勤怠提出情報一覧 -->
<div class="scroll_wrap flex flex-grow overflow-scroll">
    <div class="table_parent_div bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-sm block whitespace-nowrap">
            <thead>
                <tr class="text-left text-white bg-gray-600 sticky top-0">
                    <th class="font-thin py-3 px-2 text-center">年月</th>
                    <th class="font-thin py-3 px-2 text-center">拠点</th>
                    <th class="font-thin py-3 px-2 text-center">提出日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($kintaiCloses as $kintai_close)
                    <tr class="text-left hover:bg-theme-sub cursor-default">
                        <td class="py-1 px-2 border text-center">{{ CarbonImmutable::parse($kintai_close->close_date)->isoFormat('YYYY年MM月') }}</td>
                        <td class="py-1 px-2 border text-left">{{ $kintai_close->base_name }}</td>
                        <td class="py-1 px-2 border text-center">@if(!is_null($kintai_close->updated_at)) {{CarbonImmutable::parse($kintai_close->updated_at)->isoFormat('YYYY年MM月DD日 HH時mm分ss秒') }} @endif</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>