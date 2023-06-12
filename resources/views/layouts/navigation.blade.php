@vite(['resources/sass/navigation.scss'])

<nav id="navigation">
    <a href="{{ route('top.index') }}" class="logo">KINTAI</a>
    <ul class="links flex">
        <li class="dropdown"><a href="{{ route('punch.index') }}" class="trigger-drop">打刻</a></li>
        <li class="dropdown"><a href="{{ route('kintai_mgt.index') }}" class="trigger-drop">勤怠管理</a></li>
        <li class="dropdown"><a href="{{ route('employee_mgt.index') }}" class="trigger-drop">従業員管理</a></li>
        <li class="dropdown"><a class="trigger-drop">拠点管理</a>
            <ul class="drop">
                <li><a href="{{ route('punch_manual.index') }}">手動打刻</a></li>
                <li><a href="{{ route('customer_mgt.index') }}">荷主管理</a></li>
                <li><a href="{{ route('customer_group_mgt.index') }}">荷主グループ管理</a></li>
                <li><a href="{{ route('kintai_close.index') }}">勤怠提出</a></li>
            </ul>
        </li>
        <li class="dropdown"><a class="trigger-drop">ダウンロード</a>
            <ul class="drop">
                <li><a href="{{ route('kintai_report_download.index') }}">勤怠表</a></li>
            </ul>
        </li>
        <li class="dropdown"><a class="trigger-drop">経理管理</a>
            <ul class="drop">
                <li><a href="{{ route('kintai_close_check.index') }}">勤怠提出確認</a></li>
            </ul>
        </li>
        <li class="dropdown"><a class="trigger-drop">システム管理</a>
            <ul class="drop">
                <li><a href="{{ route('user_mgt.index') }}">ユーザー管理</a></li>
                <li><a href="{{ route('role_mgt.index') }}">権限管理</a></li>
                <li><a href="{{ route('holiday_mgt.index') }}">休日管理</a></li>
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