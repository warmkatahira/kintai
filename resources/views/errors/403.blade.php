<x-guest-layout>
    <div class="">
        <p class="">システムに接続できません。</p>
        <p class="">以下のIPアドレスをシステム担当者へご連絡下さい。</p>
        <p class="text-3xl mt-3">{{ $exception->getMessage() }}</p>
        <form method="POST" action="{{ route('ipshare.share') }}">
            @csrf
            <input type="hidden" name="info" value="{{ $exception->getMessage() }}">
            @php
                // 拠点によって場所を選択して連絡させる
                $locations = App\Enums\BaseEnum::IP_SHARE_LOCATION_LIST[Auth::user()?->base_id] ?? [];
            @endphp
            @if(empty($locations))
                <button class="text-base w-full py-3 mt-3 bg-theme-sub text-red-600">
                    <i class="las la-envelope mr-1 la-lg"></i>システム担当者へ連絡<br>
                    <p class="text-sm mt-2">このボタンでシステム担当者へ自動で情報が送信されます。</p>
                    <p class="text-lg mt-3 underline">この画面のままにしておくと、対応完了後に<br>自動でログイン後の画面になります。</p>
                </button>
            @else
                <p class="text-sm mt-3">今いる場所のボタンを押して下さい。システム担当者へ自動で情報が送信されます。</p>
                <div class="flex gap-3">
                    @foreach($locations as $location)
                        <button name="location" value="{{ $location }}" class="text-xl flex-1 py-5 mt-3 bg-theme-sub text-red-600">
                            <i class="las la-envelope mr-1 la-lg"></i>{{ $location }}から連絡
                        </button>
                    @endforeach
                </div>
                <p class="text-lg mt-3 underline text-center text-red-600">この画面のままにしておくと、対応完了後に<br>自動でログイン後の画面になります。</p>
            @endif
        </form>
    </div>
</x-guest-layout>
@vite(['resources/js/window_update.js'])