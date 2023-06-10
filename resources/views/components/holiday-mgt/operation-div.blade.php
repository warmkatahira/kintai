<div class="flex flex-row">
    <a href="{{ route('holiday_mgt.download') }}" class="text-sm bg-theme-sub py-2 px-10 mr-10">休日ダウンロード</a>
    <form method="POST" action="{{ route('holiday_mgt.upload') }}" id="holiday_upload_form" enctype="multipart/form-data" class="m-0 mr-10">
        @csrf
        <div class="flex select_file">
            <label class="text-sm hover:cursor-pointer bg-theme-sub py-2 px-10 mr-10">
                休日アップロード
                <input type="file" id="select_file" name="select_file" class="hidden">
            </label>
        </div>
    </form>
</div>