<div id="rest_time_change_modal" class="fixed hidden z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-10">
    <div class="relative top-20 mx-auto shadow-lg rounded-md bg-white">
        <!-- モーダルヘッダー -->
        <div class="flex justify-between items-center bg-theme-sub text-xl rounded-t-md px-4 py-2">
            <p class="text-2xl">休憩取得時間を選択してください</p>
        </div>
        <!-- モーダルボディー -->
        <div class="p-10">
            <div class="grid grid-cols-12">
                <p class="col-span-12 text-4xl">{{ $employeeFullName }}<span class="text-xl ml-3">さん</span></p>
                <div class="col-start-1 col-span-12 my-10">
                    <div class="grid grid-cols-12 gap-4">
                        @foreach($restTimes as $time)
                            <div class="col-span-2">
                                <input type="radio" name="rest_time_select" id="{{ $time['minute'] }}" value="{{ $time['minute'] }}" class="rest_time_select hidden" {{ $time['minute'] == $defaultRestTime ? 'checked' : '' }}>
                                <label id="{{ $time['minute'].'_label' }}" for="{{ $time['minute'] }}" class="cursor-pointer flex flex-col w-full max-w-lg mx-auto text-center border-2 rounded-lg border-gray-900 p-2 text-2xl">{{ $time['text1'] }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-span-12 grid grid-cols-12 mt-5">
                        <p class="zen-old-mincho-regular col-span-12 text-3xl text-red-500 bg-yellow-200 py-2">雇用契約上の基本休憩時間よりも短い休憩の取得の場合には、所長からの許可や<br>依頼が必要になります。本日の打刻について許可や依頼を得ていますか？</p>
                        <p id="law_violated_warning" class="zen-old-mincho-regular col-span-12 text-3xl text-white bg-red-500 py-2">休憩時間が法令に違反しているので、本日の打刻について所長へ相談してください</p>
                    </div>
                </div>
                <div class="col-span-12 grid grid-cols-12">
                    <a id="rest_time_change_yes" class="cursor-pointer rounded-lg text-white bg-blue-400 text-center p-4 col-span-5 text-4xl">許可を得ているので確定</a>
                    <a id="rest_time_change_no" class="cursor-pointer rounded-lg text-white bg-red-400 text-center p-4 col-start-8 col-span-5 text-4xl">退勤画面に戻る</a>
                </div>
            </div>
        </div>
    </div>
</div>