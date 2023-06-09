// 更新ボタンが押下されたら
$('#customer_group_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("荷主グループ情報を更新しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $("#customer_group_update_form").submit();
    }
});