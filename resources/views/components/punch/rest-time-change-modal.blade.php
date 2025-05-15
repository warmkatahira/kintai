<div id="rest_time_change_modal" class="fixed hidden z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-10">
    <div class="relative top-20 mx-auto shadow-lg rounded-md bg-white">
        <!-- モーダルヘッダー -->
        <div class="flex justify-between items-center bg-theme-sub text-xl rounded-t-md px-4 py-2">
            <p class="text-2xl">休憩取得時間を選択してください</p>
        </div>
        <!-- モーダルボディー -->
        <div class="p-10">
            <div class="grid grid-cols-12">
                <p class="col-span-7 text-4xl">{{ $employeeFullName }}<span class="text-xl ml-3">さん</span></p>
                <p id="law_violated_warning" class="col-span-5 text-4xl text-red-500 bg-yellow-200 text-center"><i class="las la-exclamation-triangle mr-1"></i>休憩時間が法令に違反しています</p>
                <div class="col-start-1 col-span-12 my-10">
                    <div class="grid grid-cols-12 gap-4">
                        @foreach($restTimes as $time)
                            <div class="col-span-2">
                                <input type="radio" name="rest_time_select" id="{{ $time['minute'] }}" value="{{ $time['minute'] }}" class="rest_time_select hidden" {{ $time['minute'] == $defaultRestTime ? 'checked' : '' }}>
                                <label id="{{ $time['minute'].'_label' }}" for="{{ $time['minute'] }}" class="cursor-pointer flex flex-col w-full max-w-lg mx-auto text-center border-2 rounded-lg border-gray-900 p-2 text-2xl">{{ $time['text1'] }}</label>
                            </div>
                        @endforeach
                    </div>
                    <p class="col-span-12 text-4xl mt-5 text-red-500 u">変更は必ず所長の許可が必要です</p>
                </div>
                <div class="col-span-12 grid grid-cols-12">
                    <a id="rest_time_change_yes" class="cursor-pointer rounded-lg text-white bg-blue-400 text-center p-4 col-span-5 text-4xl">確定</a>
                    <a id="rest_time_change_no" class="cursor-pointer rounded-lg text-white bg-red-400 text-center p-4 col-start-8 col-span-5 text-4xl">退勤画面に戻る</a>
                </div>
            </div>
        </div>
    </div>
</div>