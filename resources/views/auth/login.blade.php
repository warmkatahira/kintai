<x-guest-layout>
    <!-- バリデーションエラー -->
    <x-validation-error-msg />
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <!-- ユーザーID -->
        <x-auth.input id="user_id" label="ユーザーID" type="text" :db="null" />
        <!-- パスワード -->
        <x-auth.input id="password" label="パスワード" type="password" :db="null" />
        <div class="flex mt-4">
            <a href="{{ route('password.request') }}" class="underline mt-3">パスワードを忘れた方</a>
            <button type="submit" class="bg-black text-white text-center rounded-lg py-2 px-5 ml-auto">ログイン</button>
        </div>
    </form>
</x-guest-layout>
