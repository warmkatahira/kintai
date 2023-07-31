<x-app-layout>
    <x-page-header content="TOP"/>
    <div class="my-5">
        <div class="flex flex-row justify-evenly">
            <div class="flex flex-col w-48">
                <p class="text-xl text-center py-2 bg-black text-white">現在出勤中</p>
                <p class="text-3xl text-center py-6 bg-white">{{ number_format($now_begin_kintai_count) }}</p>
            </div>
            <div class="flex flex-col w-48">
                <p class="text-xl text-center py-2 bg-black text-white">退勤処理漏れ</p>
                <p class="text-3xl text-center py-6 bg-white">
                    @if($no_finish_kintai_count > 0 && Gate::allows('isKintaiMgtFuncAvailable'))
                        <a href="{{ route('kintai_mgt.search', 
                            [
                                'search_base_id' => Auth::user()->base_id,
                                'search_target' => App\Enums\KintaiMgtEnum::TARGET_NO_FINISH,
                                'search_work_day_from' => $no_finish_from,
                                'search_work_day_to' => $no_finish_to,
                            ]
                        ) }}" class="text-blue-600 underline">{{ number_format($no_finish_kintai_count) }}</a>
                    @else
                        {{ number_format($no_finish_kintai_count) }}
                    @endif
                </p>
            </div>
        </div>
        <div class="flex flex-row justify-center mt-5">
            <a href="{{ route('punch.index') }}" class="text-5xl text-center py-20 px-80 bg-theme-sub">打刻はこちら</a>
        </div>
    </div>
</x-app-layout>