// 更新ボタンが押下されたら
$('#role_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("権限情報を更新しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $("#role_update_form").submit();
    }
});