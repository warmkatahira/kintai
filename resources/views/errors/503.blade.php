<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>KINTAI</title>

        <!-- favicon -->
        <link rel="shortcut icon" href="{{ asset('image/favicon.svg') }}">
        
        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- Styles -->
        @vite(['resources/css/app.css'])
        <!-- Scripts -->
        @vite(['resources/js/app.js'])
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Alkatra&family=Arvo&family=Calistoga&family=Kosugi+Maru&family=Lemonada&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="mt-10">
            <div class="mt-5">
                <p class="text-2xl text-center">メンテナンス中です</p>
                <p class="text-center mt-3">現在システムメンテナンスを行っているため、システムを利用できません。</p>
                <p class="text-center mt-3">メンテナンス終了まで、しばらくお待ちください。</p>
                <p class="text-center text-2xl text-red-500 mt-3">2月27日にサイボウズで案内している内容を参照して、接続できるようにして下さい。</p>
                <p class="text-center text-2xl text-blue-500 mt-10"><a class="bg-blue-100 p-5" href="https://warm-inc.cybozu.com/o/ag.cgi?page=MyFolderMessageView&mDBID=3&mDID=7131&tp=t&flno=259#Follow" target="_blank">サイボウズへ移動する</a></p>
            </div>
        </div>
    </body>
</html>
