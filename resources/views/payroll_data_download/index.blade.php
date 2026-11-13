<x-app-layout>
    <x-page-header content="給与計算データダウンロード"/>
    <form method="GET" action="{{ route('payroll_data_download.download') }}" class="m-0">
        <div class="flex flex-row mb-2">
            <p class="w-40 bg-black text-white text-center py-2 text-sm">ダウンロード年月</p>
            <input type="month" name="date" class="w-60 text-sm" value="{{ old('date', CarbonImmutable::now()->isoFormat('YYYY-MM')) }}" required>
        </div>
        @error('date')
            <p class="text-sm text-red-600 mb-2">{{ $message }}</p>
        @enderror
        <p class="text-xs text-gray-600">※全営業所のパート従業員について、勤怠表と同じ集計方法で出力します。</p>
        <button type="submit" class="w-40 bg-blue-200 text-center rounded-lg mt-5 py-3">ダウンロード</button>
    </form>
</x-app-layout>
