@vite(['resources/js/user_mgt/user_update.js'])

<x-app-layout>
    <x-page-header content="ユーザー情報更新"/>
    <div class="my-5">
        <!-- 操作ボタン -->
        <div class="flex whitespace-nowrap mb-2">
            <a href="{{ session('back_url_1') }}" class="w-40 text-xl py-4 rounded-lg text-center bg-black text-white">戻る</a>
        </div>
        <!-- バリデーションエラー -->
        <x-validation-error-msg />
        <!-- 入力フォーム -->
        <div class="bg-white border border-gray-200 px-10 py-5 whitespace-nowrap">
            <form method="POST" action="{{ route('user_update.update') }}" class="m-0" id="user_update_form">
                @csrf
                <x-create-select label="拠点" id="base_id" :forValue="$bases" text="base_name" :db="$user->base_id" required="1" tippy="0" />
                <x-create-input label="ユーザーID" type="text" id="user_id" :db="$user->user_id" required="1" tippy="0" />
                <x-create-input label="ユーザー名(姓)" type="text" id="last_name" :db="$user->last_name" required="1" tippy="0" />
                <x-create-input label="ユーザー名(名)" type="text" id="first_name" :db="$user->first_name" required="1" tippy="0" />
                <x-create-input label="メールアドレス" type="text" id="email" :db="$user->email" required="1" tippy="0" />
                <x-create-select label="権限" id="role_id" :forValue="$roles" text="role_name" :db="$user->role_id" required="1" tippy="0" />
                <x-create-select label="ステータス" id="status" :forValue="$statuses" text="status_name" :db="$user->status" required="1" tippy="0" />
                <input type="hidden" name="id" value="{{ $user->id }}">
                <button type="button" id="user_update_enter" class="border border-blue-500 text-blue-500 bg-blue-100 py-1 px-10 mt-5">更新</button>
            </form>
        </div>
    </div>
</x-app-layout>