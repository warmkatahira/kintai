// 提出ボタンが押下されたら
$('.kintai_close_enter').on("click",function(){
    // yyyy-mmからyyyyとmmを取得
    let year = $(this).data('date').substring(0, 4);
    let month = $(this).data('date').substring(5);
    // 処理を実行するか確認
    const result = window.confirm(year + "年" + month + "月" + "の勤怠を提出しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $('#close_date').val($(this).val());
        $("#kintai_close_form").submit();
    }
});