<div class="flex flex-row items-center">
    <a href="{{ route('paid_leave_mgt.download') }}" class="text-sm bg-theme-sub py-2 px-10 mr-10">有給ダウンロード</a>
    <form method="POST" action="{{ route('paid_leave_mgt.upload') }}" id="paid_leave_upload_form" enctype="multipart/form-data" class="m-0 mr-10">
        @csrf
        <div class="flex select_file">
            <label class="text-sm hover:cursor-pointer bg-theme-sub py-2 px-10">
                有給アップロード
                <input type="file" id="select_file" name="select_file" class="hidden" accept=".csv">
            </label>
        </div>
    </form>
    <p class="text-xs text-gray-600">CSVヘッダー：従業員番号, 有給取得日, 取得日数（1 または 0.5）／同じ従業員番号・有給取得日は上書きされます</p>
</div>
