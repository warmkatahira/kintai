<div class="flex flex-row">
    @can('isEmployeeOperationAvailable')
        <a href="{{ route('employee_create.index') }}" class="text-sm bg-theme-sub py-2 px-10 mr-10">従業員追加</a>
        <a href="{{ route('employee_mgt.download') }}" class="text-sm bg-theme-sub py-2 px-10 mr-10">ダウンロード</a>
    @endcan
    <!-- 従業員CSV管理(システム管理者のみ) -->
    @can('isEmployeeCsvMgtAvailable')
        <a href="{{ route('employee_csv.download') }}" class="text-sm bg-theme-sub py-2 px-10 mr-10">マスタCSVダウンロード</a>
        <form method="POST" action="{{ route('employee_csv.upload') }}" id="employee_csv_upload_form" enctype="multipart/form-data" class="m-0 mr-10">
            @csrf
            <label class="text-sm hover:cursor-pointer bg-theme-sub py-2 px-10 inline-block">
                マスタCSVアップロード
                <input type="file" id="employee_csv_select_file" name="select_file" class="hidden" accept=".csv"
                    onchange="if(this.files.length == 0){ return; } if(window.confirm('従業員マスタのアップロードを実行しますか？\n※従業員番号が存在する従業員は上書き、存在しない従業員は追加されます。')){ this.form.submit(); }else{ this.value = ''; }">
            </label>
        </form>
    @endcan
</div>