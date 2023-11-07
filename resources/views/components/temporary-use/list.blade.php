<div class="scroll_wrap flex flex-grow overflow-scroll">
    <div class="table_parent_div bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-sm block whitespace-nowrap">
            <thead>
                <tr class="text-center text-white bg-gray-600 sticky top-0">
                    <th class="font-thin py-3 px-2">削除</th>
                    <th class="font-thin py-3 px-2">利用日</th>
                    <th class="font-thin py-3 px-2">拠点</th>
                    <th class="font-thin py-3 px-2">派遣会社</th>
                    <th class="font-thin py-3 px-2">荷主名</th>
                    <th class="font-thin py-3 px-2">人数</th>
                    <th class="font-thin py-3 px-2">稼働時間</th>
                    <th class="font-thin py-3 px-2">時給単価</th>
                    <th class="font-thin py-3 px-2">金額</th>
                    <th class="font-thin py-3 px-2">入力者</th>
                    <th class="font-thin py-3 px-2">入力日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($temporaryUses as $temporary_use)
                    <tr class="text-left hover:bg-theme-sub cursor-default">
                        <td class="py-1 px-2 border text-center">
                            <form method="POST" action="{{ route('temporary_use.delete') }}" id="temporary_use_delete_form_{{ $temporary_use->temporary_use_id }}" class="m-0">
                                @csrf
                                <input type="hidden" name="temporary_use_id" value="{{ $temporary_use->temporary_use_id }}">
                                <button type="button" id="temporary_use_id_{{ $temporary_use->temporary_use_id }}" class="temporary_use_delete_enter text-center border border-red-500 bg-red-100 text-red-500 text-xs px-2 py-1">削除</button>
                            </form>
                        </td>
                        <td class="py-1 px-2 border text-center">{{ \Carbon\CarbonImmutable::parse($temporary_use->date)->isoFormat('YYYY年MM月DD日(ddd)') }}</td>
                        <td class="py-1 px-2 border">{{ $temporary_use->base->base_name }}</td>
                        <td class="py-1 px-2 border">{{ $temporary_use->temporary_company->temporary_company_name }}</td>
                        <td class="py-1 px-2 border">{{ $temporary_use->customer->customer_name }}</td>
                        <td class="py-1 px-2 border text-right">{{ $temporary_use->people }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format(($temporary_use->working_time / 60), 2) }}</td>
                        <td class="py-1 px-2 border text-right"><i class="las la-yen-sign"></i>{{ number_format($temporary_use->hourly_rate) }}</td>
                        <td class="py-1 px-2 border text-right"><i class="las la-yen-sign"></i>
                            @if($temporary_use->temporary_company->amount_calc_item == 'working_time')
                                {{ number_format(($temporary_use->working_time / 60) * $temporary_use->hourly_rate) }}
                            @endif
                            @if($temporary_use->temporary_company->amount_calc_item == 'people')
                                {{ number_format($temporary_use->people * $temporary_use->hourly_rate) }}
                            @endif
                        </td>
                        <td class="py-1 px-2 border">{{ $temporary_use->user->last_name.' '.$temporary_use->user->first_name }}</td>
                        <td class="py-1 px-2 border">{{ \Carbon\Carbon::parse($temporary_use->created_at)->isoFormat('Y年MM月DD日 HH時mm分ss秒') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>