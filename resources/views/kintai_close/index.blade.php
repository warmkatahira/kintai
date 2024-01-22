@vite(['resources/js/kintai_close/kintai_close.js'])

<x-app-layout>
    <x-page-header content="勤怠提出"/>
    <div class="flex flex-row items-start mb-2">
        <table class="text-sm block whitespace-nowrap">
            <thead>
                <tr class="text-left text-white bg-gray-600 sticky top-0">
                    <th class="font-thin py-3 px-2 text-center">年月</th>
                    <th class="font-thin py-3 px-2 text-center">拠点確認未実施数</th>
                    <th class="font-thin py-3 px-2 text-center">提出</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($kintais as $kintai)
                    <tr class="text-left hover:bg-theme-sub cursor-default">
                        <td class="py-1 px-2 border text-center">{{ \Carbon\CarbonImmutable::parse($kintai->date)->isoFormat('YYYY年MM月') }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($kintai->countNoBaseCheckKintai($kintai->date)) }}</td>
                        <td class="py-1 px-2 border text-center">
                            @if(number_format($kintai->countNoBaseCheckKintai($kintai->date)) == 0)
                                <button type="button" class="kintai_close_enter text-center border border-blue-500 bg-blue-100 text-blue-500 text-xs px-2 py-1" value="{{ $kintai->date }}" data-date="{{ $kintai->date }}">提出</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <form method="POST" action="{{ route('kintai_close.close') }}" id="kintai_close_form" class="m-0">
        @csrf
        <input type="hidden" id="close_date" name="close_date">
    </form>
    <!-- 勤怠なし従業員表示 -->
    @if(session('kintai_nothing_employees'))
        <div class="bg-red-500 text-white px-5 pb-5">
            <p class="text-xl py-5"><i class="las la-exclamation-triangle la-lg mr-1"></i>勤怠なし従業員一覧</p>
            @foreach(session('kintai_nothing_employees') as $employee)
                <p class="py-1">{{ $employee->employee_last_name .' '. $employee->employee_first_name }}</p>
            @endforeach
            <div class="border-t border-white pt-3">
                <i class="las la-info-circle la-lg mr-1"></i>退職者の場合は、従業員の「有効/無効」を「無効」に変更してから提出を行って下さい。<br>
                <i class="las la-info-circle la-lg mr-1"></i>退職者ではない場合は、再度提出処理を行って下さい。<br>
            </div>
        </div>
    @endif
</x-app-layout>
