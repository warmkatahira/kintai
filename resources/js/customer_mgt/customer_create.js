// 追加ボタンが押下されたら
$('#customer_create_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("荷主を追加しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $("#customer_create_form").submit();
    }
});