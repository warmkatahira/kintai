@vite(['resources/js/download/download.js'])

<x-app-layout>
    <x-page-header content="勤怠表ダウンロード"/>
    <form method="GET" action="{{ route('kintai_report_download.download') }}" id="download_form" class="m-0">
        <div class="flex flex-row mb-2">
            <p class="w-40 bg-black text-white text-center py-2 text-sm">拠点</p>
            <select name="base_id" class="text-sm w-60">
                @foreach($bases as $base)
                    <option value="{{ $base->base_id }}" @if($base->base_id == Auth::user()->base_id) selected @endif>{{ $base->base_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-row mb-2">
            <p class="w-40 bg-black text-white text-center py-2 text-sm">ダウンロード年月</p>
            <input type="month" name="date" class="w-60 text-sm" value="{{ CarbonImmutable::now()->isoFormat('YYYY-MM') }}">
        </div>
    </form>
    <button type="button" id="download_enter" class="w-40 bg-blue-200 text-center rounded-lg mt-5 py-3">ダウンロード</button>
</x-app-layout>
