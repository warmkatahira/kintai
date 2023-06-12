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
</x-app-layout>
