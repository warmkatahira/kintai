// IPチェックなし一括更新ボタンが押下されたら
$('.no_ip_check_bulk_enter').on("click",function(){
    const base_id = $('#bulk_base_id').val();
    // 拠点が選択されていなければ処理しない
    if(!base_id) {
        window.alert("拠点を選択してください。");
        return;
    }
    const base_name = $('#bulk_base_id option:selected').text();
    // 処理を実行するか確認
    const result = window.confirm(base_name + "のステータスが有効な全ユーザーのIPチェックなしを「" + $(this).data('label') + "」にしますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $('#no_ip_check_bulk_value').val($(this).data('value'));
        $("#no_ip_check_bulk_update_form").submit();
    }
});
