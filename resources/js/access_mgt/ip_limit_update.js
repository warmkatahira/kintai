// 更新ボタンが押下されたら
$('#ip_limit_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("IPを更新しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $("#ip_limit_update_form").submit();
    }
});