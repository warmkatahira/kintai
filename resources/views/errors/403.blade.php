<x-guest-layout>
    <div class="">
        <p class="">システムに接続できません。</p>
        <p class="">以下のIPアドレスをシステム担当者へご連絡下さい。</p>
        <p class="text-3xl mt-3">{{ $exception->getMessage() }}</p>
        <form method="POST" action="{{ route('ipshare.share') }}">
            @csrf
            <input type="hidden" name="info" value="{{ $exception->getMessage() }}">
            <button class="text-base w-full py-3 mt-3 bg-theme-sub text-red-600">
                <i class="las la-envelope mr-1 la-lg"></i>システム担当者へ連絡<br>
                <span class="text-sm">
                    このボタンでシステム担当者へ自動で情報が送信されます。<br>
                    対応完了後、メール又は電話でご連絡します。
                </span>
            </button>
        </form>
    </div>
</x-guest-layout>