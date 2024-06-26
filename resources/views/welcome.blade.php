<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>KINTAI</title>

        <!-- favicon -->
        <link rel="shortcut icon" href="{{ asset('image/favicon.svg') }}">

        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/sass/theme.scss'])
        <!-- Scripts -->
        @vite(['resources/js/app.js'])
        <!-- LINE AWESOME -->
        <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Alkatra&family=Arvo&family=Calistoga&family=Kosugi+Maru&family=Lemonada&display=swap" rel="stylesheet">
        <!-- Lordicon -->
        <script src="https://cdn.lordicon.com/pzdvqjsp.js"></script>
        <!-- toastr.js -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <!-- Tippy.js -->
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="https://unpkg.com/tippy.js@6"></script>
    </head>
    <body>
        <div class="flex mt-3">
            <!-- アラート表示 -->
            <x-alert/>
            @auth
                <a href="{{ route('top.index') }}" class="ml-auto mr-10">ホーム</a>
            @else
                <a href="{{ route('login') }}" class="ml-auto">ログイン</a>
                <a href="{{ route('register') }}" class="ml-10 mr-10">ユーザー登録</a>
            @endauth
        </div>
        <div class="text-center">
            <p class="text-2xl">勤怠管理システム</p>
            <p class="text-7xl Calistoga">KINTAI</p>
        </div>
        <div class="text-center mt-5">
            <img src="{{ asset('image/favicon.svg') }}" class="inline-block w-52">
        </div>
    </body>
</html>
