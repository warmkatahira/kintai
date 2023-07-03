// 打刻ボタンが押下されたら
$('#punch_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("手動打刻(出勤のみ)を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $("#punch_manual_begin_only_form").submit();
    }
});