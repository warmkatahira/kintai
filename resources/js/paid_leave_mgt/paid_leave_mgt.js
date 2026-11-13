// CSVアップロードでファイルが選択されたら
$('.select_file input[type=file]').on("change",function(){
    // ファイルが選択されていなければ何もしない
    if(this.files.length == 0) {
        return;
    }
    // 処理を実行するか確認
    const result = window.confirm("有給アップロードを実行しますか？\n※同じ従業員番号・有給取得日のデータは上書きされます。");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら要素をクリア
    if(result == true) {
        $("#paid_leave_upload_form").submit();
        return;
    }
    $('#select_file').val(null);
});

// 削除ボタンが押下されたら
$(".paid_leave_delete_enter").on("click",function(){
    // アンダーバーで分割
    var split = $(this).attr('id').split('_');
    // 削除対象の有給IDを取得
    const paid_leave_id = split[split.length - 1];
    // 処理を実行するか確認
    const result = window.confirm("削除を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $("#paid_leave_delete_form_" + paid_leave_id).submit();
    }
});
