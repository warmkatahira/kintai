@vite(['resources/js/kintai_mgt/kintai_detail.js'])

<x-app-layout>
    <x-page-header content="勤怠詳細"/>
    <div class="my-5">
        <!-- バリデーションエラー -->
        <x-validation-error-msg />
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap">
            <a href="{{ session('back_url_1') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
            <!-- 拠点やロック有無などを全て考慮して操作できるか -->
            @can('isKintaiOperationAllAvailable', [$kintai->employee->base_id, $kintai->locked_at])
                <a href="{{ route('punch_update.index', ['kintai_id' => $kintai->kintai_id]) }}" class="w-40 text-xl py-4 rounded-lg text-center bg-blue-200 ml-auto">修正</a>
                <form method="POST" action="{{ route('kintai_delete.delete') }}" id="kintai_delete_form" class="m-0 ml-10">
                    @csrf
                    <input type="hidden" name="kintai_id" value="{{ $kintai->kintai_id }}">
                    <button type="button" id="kintai_delete" class="w-40 text-xl py-4 rounded-lg text-center bg-red-500 text-white">削除</button>
                </form>
            @endcan
        </div>
        <div class="flex flex-col">
            <p class="border-l-8 border-theme-sub text-xl pl-3 my-3">コメント</p>
            <form method="POST" action="{{ route('kintai_update.comment_update') }}" id="comment_update_form" class="m-0 flex flex-row">
                @csrf
                <input type="text" name="comment" class="text-sm w-96" value="{{ old('comment', $kintai->comment) }}" autocomplete="off">
                <input type="hidden" name="kintai_id" value="{{ $kintai->kintai_id }}">
                <!-- 拠点やロック有無などを全て考慮して操作できるか -->
                @can('isCommentOperationAllAvailable', [$kintai->employee->base_id, $kintai->locked_at])
                    <button type="button" id="comment_update" class="bg-blue-600 text-white text-sm text-center px-5">更新</button>
                @endcan
            </form>
        </div>
        @if($kintai->employee->base->is_special_working_time_available && !is_null($kintai->finish_time))
            @if(Auth::user()->base_id === $kintai->employee->base_id || Auth::user()->can('isSpecialWorkingTimeDispAvailable'))
                <div class="flex flex-col">
                    <p class="border-l-8 border-theme-sub text-xl pl-3 my-3">特別稼働時間(分単位)</p>
                    <form method="POST" action="{{ route('kintai_update.special_working_time_update') }}" id="special_working_time_update_form" class="m-0 flex flex-row">
                        @csrf
                        <input type="text" name="special_working_time" class="text-sm w-96" value="{{ old('special_working_time', $kintai->special_working_time) }}" autocomplete="off">
                        <input type="hidden" name="kintai_id" value="{{ $kintai->kintai_id }}">
                        <!-- 拠点やロック有無などを全て考慮して操作できるか -->
                        @can('isCommentOperationAllAvailable', [$kintai->employee->base_id, $kintai->locked_at])
                            <button type="button" id="special_working_time_update" class="bg-blue-600 text-white text-sm text-center px-5">更新</button>
                        @endcan
                    </form>
                </div>
            @endif
        @endif
        <div class="flex flex-col">
            <p class="border-l-8 border-theme-sub text-xl pl-3 my-3">勤怠概要</p>
            <x-detail-div label="出勤日" :value="CarbonImmutable::parse($kintai->work_day)->isoFormat('YYYY年MM月DD日(ddd)')" class="w-40 text-center" />
            <x-detail-div label="拠点" :value="$kintai->employee->base->base_name" class="w-40 text-center" />
            <div class="flex flex-row">
                <x-detail-div label="従業員区分" :value="$kintai->employee->employee_category->employee_category_name" class="w-40 text-center" />
                <x-detail-div label="従業員名" :value="$kintai->employee->employee_last_name.' '.$kintai->employee->employee_first_name" class="w-40 text-center" />
            </div>
            <div class="flex flex-row">
                <x-detail-div label="出勤時間" :value="substr($kintai->begin_time_adj, 0, 5)" class="w-40 text-center" />
                <x-detail-div label="退勤時間" :value="substr($kintai->finish_time_adj, 0, 5)" class="w-40 text-center" />
            </div>
            <div class="flex flex-row">
                <x-detail-div label="外出時間" :value="substr($kintai->out_time_adj, 0, 5)" class="w-40 text-center" />
                <x-detail-div label="戻り時間" :value="substr($kintai->return_time_adj, 0, 5)" class="w-40 text-center" />
                <x-detail-div label="外出戻り時間" :value="$kintai->out_return_time == 0 ? '' : number_format($kintai->out_return_time / 60, 2)" class="w-40 text-center" />
            </div>
            <div class="flex flex-row">
                <x-detail-div label="休憩取得時間" :value="is_null($kintai->rest_time) ? '' : number_format($kintai->rest_time / 60, 2)" class="w-40 text-center" />
                <x-detail-div label="休憩未取得時間" :value="is_null($kintai->no_rest_time) ? '' : number_format($kintai->no_rest_time / 60, 2)" class="w-40 text-center" />
                @if(Gate::check('isAddRestTimeDispAvailable') || $add_rest_available)
                    <x-detail-div label="追加休憩取得時間" :value="is_null($kintai->add_rest_time) ? '' : number_format($kintai->add_rest_time / 60, 2)" class="w-40 text-center" />
                @endif
            </div>
            <div class="flex flex-row">
                <x-detail-div label="稼働時間" :value="is_null($kintai->working_time) ? '' : number_format($kintai->working_time / 60, 2)" class="w-40 text-center" />
                @if(Gate::check('isShortTimeInfoAvailable') || $kintai->employee->over_time_start == 0)
                    <x-detail-div label="残業時間" :value="is_null($kintai->over_time) ? '' : number_format($kintai->over_time /60, 2)" class="w-40 text-center" />
                @else
                    <x-detail-div label="残業時間" :value="number_format(0 /60, 2)" class="w-40 text-center" />
                @endif
            </div>
            <div class="flex flex-row">
                <x-detail-div label="早出" :value="$kintai->is_early_worked == 1 ? '○' : ''" class="w-40 text-center" />
                <x-detail-div label="手動打刻" :value="$kintai->is_manual_punched == 1 ? '○' : ''" class="w-40 text-center" />
                <x-detail-div label="修正" :value="$kintai->is_modified == 1 ? '○' : ''" class="w-40 text-center" />
            </div>
        </div>
        <div class="flex flex-col">
            <p class="border-l-8 border-theme-sub text-xl pl-3 my-3">勤怠詳細</p>
            <div class="grid grid-cols-12 gap-4">
                @foreach($kintai_details as $kintai_detail)
                    <div class="col-span-3 bg-theme-sub py-5 rounded-lg text-center">
                        <p class="">{{ number_format($kintai_detail->customer_working_time / 60, 2) }}</p>
                        <p class="">{{ $kintai_detail->customer_name }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>