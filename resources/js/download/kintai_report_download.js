// ダウンロードボタンが押下されたら
$('#kintai_report_download_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("ダウンロードを実施しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $("#kintai_report_download_form").submit();
    }
});