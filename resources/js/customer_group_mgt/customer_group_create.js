// 追加ボタンが押下されたら
$('#customer_group_create_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("荷主グループを追加しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $("#customer_group_create_form").submit();
    }
});