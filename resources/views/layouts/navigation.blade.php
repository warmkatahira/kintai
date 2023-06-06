<link rel="stylesheet" href="{{ asset('css/navigation.css') }}">

<nav id="navigation">
    <a href="{{ route('top.index') }}" class="logo">KINTAI</a>
    <ul class="links flex">
        <li class="dropdown"><a href="#" class="trigger-drop">受注</a>
            <ul class="drop">
                <li><a href="">受注インポート</a></li>
            </ul>
        </li>
        <li class="dropdown"><a href="#" class="trigger-drop">受注</a>
            <ul class="drop">
                <li><a href="">受注インポート</a></li>
            </ul>
        </li>
        <li class="dropdown"><a href="#" class="trigger-drop">受注</a>
            <ul class="drop">
                <li><a href="">受注インポート</a></li>
            </ul>
        </li>
    </ul>
    <ul class="user_info">
        <li class="dropdown"><a href="#" class="trigger-drop">{{ Auth::user()->last_name.' '.Auth::user()->first_name.'さん' }}</a>
            <ul class="drop">
                <li><a href="">アカウント</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="">ログアウト</button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<!-- アラート表示 -->
<x-alert/>