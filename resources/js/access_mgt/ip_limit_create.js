// 追加ボタンが押下されたら
$('#ip_limit_create_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("IPを追加しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $("#ip_limit_create_form").submit();
    }
});