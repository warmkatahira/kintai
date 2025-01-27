// 削除ボタンが押下されたら
$('.ip_limit_delete_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("IPを削除しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $("#delete_ip_limit_id").val($(this).data('ip-limit-id'));
        $("#ip_limit_delete_form").submit();
    }
});