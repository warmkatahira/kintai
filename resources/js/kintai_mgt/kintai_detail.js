// 削除ボタンが押下されたら
$('#kintai_delete').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("勤怠を削除しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $("#kintai_delete_form").submit();
    }
});

// コメント更新ボタンが押下されたら
$('#comment_update').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("コメントを更新しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $("#comment_update_form").submit();
    }
});