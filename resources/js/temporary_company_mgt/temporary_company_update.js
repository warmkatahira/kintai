// 更新ボタンが押下されたら
$('#temporary_company_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("派遣会社情報を更新しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $("#temporary_company_update_form").submit();
    }
});