// 打刻確認モーダルを開く
$('.punch_enter').on("click",function(){
    // モーダルを表示
    $('#punch_confirm_modal').removeClass('hidden');
    // 従業員ID or 勤怠IDと従業員名を出力
    $('#punch_id').val($(this).val());
    $('#punch_target_employee_name').html($(this).html() + ' さん');
});

// 打刻確認モーダルを閉じる
$('#punch_confirm_cancel').on("click",function(){
    // モーダルを非表示
    $('#punch_confirm_modal').addClass('hidden');
});

// 打刻実行ボタンが押下されたら
$('#punch_confirm_enter').on("click",function(){
    // フォームをサブミット
    $('#punch_enter_form').submit();
});