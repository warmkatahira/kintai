@vite(['resources/js/download/download.js'])

<x-app-layout>
    <x-page-header content="データダウンロード"/>
    <x-validation-error-msg />
    <form method="GET" action="{{ route('data_download.download') }}" id="download_form" class="m-0">
        <div class="flex flex-row mb-2">
            <p class="w-40 bg-black text-white text-center py-2 text-sm">拠点</p>
            <select name="base_id" class="text-sm w-60">
                <option value="all">全拠点</option>
                @foreach($bases as $base)
                    <option value="{{ $base->base_id }}" @if($base->base_id == Auth::user()->base_id) selected @endif>{{ $base->base_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-row mb-2">
            <p class="w-40 bg-black text-white text-center py-2 text-sm">ダウンロード期間</p>
            <input type="date" name="from_date" class="w-40 text-sm" value="{{ CarbonImmutable::now()->isoFormat('YYYY-MM-DD') }}">
            <p class="w-10 text-center py-2 text-sm">～</p>
            <input type="date" name="to_date" class="w-40 text-sm" value="{{ CarbonImmutable::now()->isoFormat('YYYY-MM-DD') }}">
        </div>
        <div class="flex flex-row mb-2">
            <p class="w-40 bg-black text-white text-center py-2 text-sm">ダウンロード項目</p>
            <select name="download_item" class="text-sm w-60">
                @foreach($download_lists as $download_list)
                    <option value="{{ $download_list['key'] }}">{{ $download_list['value'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-row mb-2">
            <p class="w-40 bg-black text-white text-center py-2 text-sm">集計単位</p>
            <select name="aggregate_unit" class="w-60 text-sm">
                <option value="日単位">日単位</option>
                <option value="月単位">月単位</option>
            </select>
        </div>
    </form>
    <button type="button" id="download_enter" class="w-40 bg-blue-200 text-center rounded-lg mt-5 py-3">ダウンロード</button>
</x-app-layout>
