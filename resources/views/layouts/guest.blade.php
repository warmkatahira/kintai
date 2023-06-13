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
        @vite(['resources/css/app.css', 'resources/sass/theme.scss'])
        <!-- Scripts -->
        @vite(['resources/js/app.js'])
        <!-- LINE AWESOME -->
        <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Alkatra&family=Arvo&family=Kosugi+Maru&family=Lemonada&display=swap" rel="stylesheet">
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
        <div class="flex flex-col items-center my-5">
            <!-- アラート表示 -->
            <x-alert/>
            <div>
                <a href="{{ route('welcome.index') }}">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 shadow-md overflow-hidden sm:rounded-lg bg-white">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
