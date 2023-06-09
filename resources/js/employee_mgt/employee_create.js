// 追加ボタンが押下されたら
$('#employee_create_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("従業員を追加しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $("#employee_create_form").submit();
    }
});

// 月間稼働可能時間
tippy('.tippy_monthly_workable_time', {
    content: "設定する場合は、0.25単位で入力して下さい。<br>" +
             "設定しない場合は、0を入力して下さい。",
    duration: 500,
    allowHTML: true,
    maxWidth: 'none',
    width: 450,
    placement: 'right',
});

// 残業開始時間
tippy('.tippy_over_time_start', {
    content: "設定する場合は、0.25単位で入力して下さい。<br>" +
             "設定しない場合は、0を入力して下さい。",
    duration: 500,
    allowHTML: true,
    maxWidth: 'none',
    width: 450,
    placement: 'right',
});