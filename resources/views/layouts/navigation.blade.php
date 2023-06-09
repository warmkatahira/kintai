@vite(['resources/sass/navigation.scss'])

<nav id="navigation">
    <a href="{{ route('top.index') }}" class="logo">KINTAI</a>
    <ul class="links flex">
        <li class="dropdown"><a href="{{ route('punch.index') }}" class="trigger-drop">打刻</a></li>
        <li class="dropdown"><a href="{{ route('kintai_mgt.index') }}" class="trigger-drop">勤怠管理</a></li>
        <li class="dropdown"><a href="{{ route('employee_mgt.index') }}" class="trigger-drop">従業員管理</a></li>
        <li class="dropdown"><a href="#" class="trigger-drop">拠点管理</a>
            <ul class="drop">
                <li><a href="{{ route('punch_manual.index') }}">手動打刻</a></li>
                <li><a href="{{ route('customer_mgt.index') }}">荷主管理</a></li>
                <li><a href="">荷主グループ管理</a></li>
                <li><a href="">勤怠提出</a></li>
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