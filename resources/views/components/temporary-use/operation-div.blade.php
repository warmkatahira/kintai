<div class="flex flex-row mr-10">
    <a href="{{ route('temporary_use.create') }}" class="text-sm bg-theme-sub py-2 px-10 mr-10">派遣利用入力</a>
    <a href="{{ route('temporary_use.download') }}" class="text-sm bg-theme-sub py-2 px-10">ダウンロード</a>
</div>
<div class="flex flex-row">
    <p class="bg-gray-600 text-white text-sm py-2 px-10"><i class="las la-user-friends la-lg mr-1"></i>合計人数</p>
    <p class="bg-white py-2 px-10">{{ $temporaryUseTotal['total_people'] }}</p>
</div>
<div class="flex flex-row">
    <p class="bg-gray-600 text-white text-sm py-2 px-10"><i class="las la-stopwatch la-lg mr-1"></i>合計稼働時間</p>
    <p class="bg-white py-2 px-10">{{ number_format(($temporaryUseTotal['total_working_time'] / 60), 2) }}</p>
</div>
<div class="flex flex-row">
    <p class="bg-gray-600 text-white text-sm py-2 px-10"><i class="las la-money-bill la-lg mr-1"></i>合計金額</p>
    <p class="bg-white py-2 px-10"><i class="las la-yen-sign"></i>{{ number_format($temporaryUseTotal['total_amount']) }}</p>
</div>