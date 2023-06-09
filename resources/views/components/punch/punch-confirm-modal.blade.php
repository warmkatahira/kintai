<!-- 打刻確認モーダル -->
<div id="punch_confirm_modal" class="fixed hidden z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-10">
    <div class="relative top-32 mx-auto shadow-lg rounded-md bg-white">
        <!-- モーダルヘッダー -->
        <div class="flex justify-between items-center bg-theme-sub text-xl rounded-t-md px-4 py-2">
            <p class="text-2xl">{{ $proc }}打刻を実行しますか？</p>
        </div>
        <!-- モーダルボディー -->
        <div class="p-10">
            <div class="grid grid-cols-12">
                <p id="punch_target_employee_name" class="col-span-12 text-4xl mb-10"></p>
                @if($earlyWorkAvailable)
                    <div class="col-span-12 grid grid-cols-12 gap-4 mb-5">
                        <p id="message" class="col-span-12 text-4xl hidden">早出時間を選択して、出勤ボタンを押して下さい。</p>
                        <div id="early_work_select_info_div" class="col-span-12 grid grid-cols-12 gap-4 hidden">
                            @foreach($earlyWorkSelectInfo as $info)
                                <div class="col-span-3">
                                    <input type="radio" name="early_work_select_info" value="{{ $info }}" id="{{ $info }}" class="early_work_select_info hidden">
                                    <label id="{{ $info.'_label' }}" for="{{ $info }}" class="cursor-pointer flex flex-col w-full max-w-lg mx-auto text-center border-2 rounded-lg border-gray-900 p-2 text-2xl">{{ $info }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <a id="punch_confirm_enter" class="cursor-pointer rounded-lg text-white bg-black text-center p-4 col-span-5 text-4xl">{{ $proc }}</a>
                <a id="punch_confirm_cancel" class="cursor-pointer rounded-lg text-white bg-red-400 text-center p-4 col-start-8 col-span-5 text-4xl">キャンセル</a>
            </div>
        </div>
    </div>
</div>