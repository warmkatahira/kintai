<div class="scroll_wrap flex flex-grow overflow-scroll">
    <div class="table_parent_div bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-sm block whitespace-nowrap">
            <thead>
                <tr class="text-center text-white bg-gray-600 sticky top-0">
                    <th class="font-thin py-3 px-2">更新</th>
                    <th class="font-thin py-3 px-2">派遣会社名</th>
                    <th class="font-thin py-3 px-2">時給単価</th>
                    <th class="font-thin py-3 px-2">更新日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($temporaryCompanies as $temporary_company)
                    <tr class="hover:bg-theme-sub cursor-default">
                        <td class="py-1 px-2 border text-center">
                            <a href="{{ route('temporary_company_update.index', ['temporary_company_id' => $temporary_company->temporary_company_id]) }}" class="text-center border border-blue-500 bg-blue-100 text-blue-500 text-xs px-2 py-1">更新</a>
                        </td>
                        <td class="py-1 px-2 border text-left">{{ $temporary_company->temporary_company_name }}</td>
                        <td class="py-1 px-2 border text-right"><i class="las la-yen-sign"></i>{{ number_format($temporary_company->hourly_rate) }}</td>
                        <td class="py-1 px-2 border">{{ \Carbon\Carbon::parse($temporary_company->updated_at)->isoFormat('Y年MM月DD日 HH時mm分ss秒') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>