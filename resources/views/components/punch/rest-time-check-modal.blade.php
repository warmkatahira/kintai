<div id="rest_time_check_modal" class="fixed z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-10">
    <div class="relative top-20 mx-auto shadow-lg rounded-md bg-white">
        <!-- モーダルヘッダー -->
        <div class="flex justify-between items-center bg-theme-sub text-xl rounded-t-md px-4 py-2">
            <p class="text-2xl">休憩取得時間は以下の内容で合っていますか？</p>
        </div>
        <!-- モーダルボディー -->
        <div class="p-10">
            <div class="grid grid-cols-12">
                <p class="col-span-12 text-4xl mb-10">{{ $employeeFullName }}<span class="text-xl ml-3">さん</span></p>
                <p class="col-span-12 text-5xl mb-10">{{ number_format($defaultRestTime / 60, 2) .' 時間' }}</p>
                <a id="rest_time_check_yes" class="cursor-pointer rounded-lg text-white bg-blue-400 text-center p-4 col-span-5 text-4xl">はい</a>
                <a id="rest_time_check_no" class="cursor-pointer rounded-lg text-white bg-red-400 text-center p-4 col-start-8 col-span-5 text-4xl">いいえ</a>
            </div>
        </div>
    </div>
</div>