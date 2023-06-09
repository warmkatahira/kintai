// 従業員名ボタンが押下されたら
$('.punch_enter').on("click",function(){
    // 勤怠IDをセット
    $('#punch_id').val($(this).val());
    // 次の画面へ移動
    $('#punch_enter_form').submit();
});