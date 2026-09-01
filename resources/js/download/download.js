// ダウンロードボタンが押下されたら
$('#download_enter').on("click", function(){
    const $button  = $(this);
    const checkUrl = $button.data('check-url');
    const base_id  = $('#download_form select[name="base_id"]').val();
    const date     = $('#download_form input[name="date"]').val();

    if(!date){
        window.alert("ダウンロード年月を選択してください。");
        return;
    }

    // 二重クリック防止
    $button.prop('disabled', true);

    // 提出状況を確認
    $.get(checkUrl, { base_id: base_id, date: date })
        .done(function(res){
            // 未提出なら警告を出す
            if(!res.is_submitted){
                const result = window.confirm(
                    "選択された年月の勤怠は、まだ提出（締め）されていません。\n" +
                    "確定前のデータが出力されますが、ダウンロードしますか？"
                );
                if(!result){
                    return;
                }
            }
            $("#download_form").submit();
        })
        .fail(function(){
            window.alert("提出状況の確認に失敗しました。時間をおいて再度お試しください。");
        })
        .always(function(){
            $button.prop('disabled', false);
        });
});

// ダウンロードボタンが押下されたら
$('#download_data_enter').on("click", function(){
    $("#download_data_form").submit();
});