// 出勤タイプボタンが押されたら
$('#punch_begin_type').on("click",function(){
    if ($(this).is(':checked')) {
        // チェックボックスがオンの場合の処理
        $('#punch_begin_type_label').text('早出');
        // 早出時間の選択を初期化
        var early_work_select_info = document.getElementsByName("early_work_select_info");
        for(var i = 0; i < early_work_select_info.length; i++){
            early_work_select_info[i].checked - false;
            const element = document.getElementById(early_work_select_info[i].id + '_label');
            element.classList.remove('bg-blue-200');
        }
        // 出勤ボタンを非表示にして、早出時間選択を表示
        $('#punch_confirm_enter').addClass('hidden');
        $('#early_work_select_info_div').removeClass('hidden');
        $('#message').removeClass('hidden');
    } else {
        // チェックボックスがオフの場合の処理
        $('#punch_begin_type_label').text('通常');
        // 出勤ボタンを表示して、早出時間選択を非表示
        $('#punch_confirm_enter').removeClass('hidden');
        $('#early_work_select_info_div').addClass('hidden');
        $('#message').addClass('hidden');
    }
});

// 早出時間が選択されたら
$(".early_work_select_info").on("click",function(){
    var early_work_select_info = document.getElementsByName("early_work_select_info");
    for(var i = 0; i < early_work_select_info.length; i++){
        if(early_work_select_info[i].checked) {
            // 選択要素のCSSを調整
            const element = document.getElementById(early_work_select_info[i].id + '_label');
            element.classList.add('bg-blue-200');
        }
        if(!early_work_select_info[i].checked) {
            // 非選択要素のCSSを調整
            const element = document.getElementById(early_work_select_info[i].id + '_label');
            element.classList.remove('bg-blue-200');
        }
    }
    // 出勤ボタンを表示
    $("#punch_confirm_enter").removeClass('hidden');
});