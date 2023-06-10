// 追加ボタンが押下されたら
$('#role_create_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("権限を追加しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $("#role_create_form").submit();
    }
});