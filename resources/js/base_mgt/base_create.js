// 追加ボタンが押下されたら
$('#base_create_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("拠点を追加しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $("#base_create_form").submit();
    }
});