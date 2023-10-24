// 追加ボタンが押下されたら
$('#temporary_company_create_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("派遣会社を追加しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $("#temporary_company_create_form").submit();
    }
});