<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        パスワードを忘れた方は、登録している自身のメールアドレスを入力して送信ボタンを押して下さい。<br>
        パスワードリセット用のメールが届くので、そちらから再設定して下さい。
    </div>
    <!-- バリデーションエラー -->
    <x-validation-error-msg />
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <!-- メールアドレス -->
        <x-auth.input id="email" label="メールアドレス" type="email" :db="null" />
        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="bg-black text-white text-center rounded-lg py-2 px-5">送信</button>
        </div>
    </form>
</x-guest-layout>