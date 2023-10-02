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
        @vite(['resources/css/app.css', 'resources/sass/theme.scss', 'resources/sass/scroll.scss'])
        <!-- Scripts -->
        @vite(['resources/js/app.js', 'resources/js/search_date_period.js'])
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
        <p>※このメールは自動送信です</p>
        <p>勤怠で確認が必要なものがあります。</p>
        <table style="border-collapse: collapse; width: 100%;">
            <tr>
              <th style="border: 1px solid #000;">営業所</th>
              <th style="border: 1px solid #000;">従業員名</th>
              <th style="border: 1px solid #000;">出勤日</th>
              <th style="border: 1px solid #000;">メッセージ</th>
            </tr>
            @foreach($data as $item)
                <tr>
                    <td style="border: 1px solid #000;">{{ $item['base_name'] }}</td>
                    <td style="border: 1px solid #000;">{{ $item['employee_name'] }}</td>
                    <td style="border: 1px solid #000;">{{ $item['work_day'] }}</td>
                    <td style="border: 1px solid #000;">{{ $item['message'] }}</td>
                </tr>
            @endforeach
        </table>
    </body>
</html>