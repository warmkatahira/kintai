<div class="flex flex-row">
    @can('isBaseCheckAvailable')
        <form method="POST" action="{{ route('kintai_update.base_check') }}" id="base_check_form" class="m-0">
            @csrf
            <button type="button" id="base_check_enter" class="text-sm bg-theme-sub py-2 px-10 mr-10">拠点確認</button>
        </form>
    @endcan
</div>