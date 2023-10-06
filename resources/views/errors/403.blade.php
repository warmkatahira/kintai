<x-guest-layout>
    <div class="">
        <p class="">システムに接続できません。</p>
        <p class="">以下のIPアドレスをシステム課へご連絡下さい。</p>
        <p class="text-3xl mt-3">{{ $exception->getMessage() }}</p>
    </div>
</x-guest-layout>