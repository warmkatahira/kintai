// 打刻確認モーダルを開く
$('.punch_enter').on("click",function(){
    // モーダルを表示
    $('#punch_confirm_modal').removeClass('hidden');
    // 従業員ID or 勤怠IDと従業員名を出力
    $('#punch_id').val($(this).val());
    $('#punch_target_employee_name').html($(this).html() + ' さん');
    if($('#lunch_break_check_message').val() !== 'none'){
       $('#lunch_break_check_select_div').removeClass('hidden'); 
    }
});

// 打刻確認モーダルを閉じる
$('#punch_confirm_cancel').on("click",function(){
    // モーダルを非表示
    $('#punch_confirm_modal').addClass('hidden');
});

// 打刻実行ボタンが押下されたら
$('#punch_confirm_enter').on("click",function(){
    try {
        if($('#lunch_break_check_message').val() !== 'none'){
            if($('[name="lunch_break_check_select"]').length > 0 && $('[name="lunch_break_check_select"]:checked').length == 0){
                throw new Error('確認が選択されていません。');
            }
        }
        // フォームをサブミット
        $('#punch_enter_form').submit();
    } catch (e) {
        alert(e.message);
        return false;
    }
});

// 確認が選択されたら
$(".lunch_break_check_select").on("click",function(){
    var lunch_break_check_select = document.getElementsByName("lunch_break_check_select");
    for(var i = 0; i < lunch_break_check_select.length; i++){
        if(lunch_break_check_select[i].checked) {
            // 選択要素のCSSを調整
            const element = document.getElementById(lunch_break_check_select[i].id + '_label');
            element.classList.add('bg-blue-200');
        }
        if(!lunch_break_check_select[i].checked) {
            // 非選択要素のCSSを調整
            const element = document.getElementById(lunch_break_check_select[i].id + '_label');
            element.classList.remove('bg-blue-200');
        }
    }
});