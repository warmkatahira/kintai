// 派遣会社選択が押下されたら
$(".temporary_company_select").on("click",function(){
    // 要素を取得
    var temporary_companies = document.getElementsByName("temporary_company_id");
    // 
    for(var i = 0; i < temporary_companies.length; i++){
        const element = document.getElementById(temporary_companies[i].id + '_label');
        if(temporary_companies[i].checked) {
            element.classList.add('bg-blue-200');
        }
        if(!temporary_companies[i].checked) {
            // 非選択要素のCSSを調整
            element.classList.remove('bg-blue-200');
        }
    }
});

// 荷主選択が押下されたら
$(".customer_select").on("click",function(){
    // 要素を取得
    var customers = document.getElementsByName("customer_id");
    // 
    for(var i = 0; i < customers.length; i++){
        const element = document.getElementById(customers[i].id + '_label');
        if(customers[i].checked) {
            element.classList.add('bg-blue-200');
        }
        if(!customers[i].checked) {
            // 非選択要素のCSSを調整
            element.classList.remove('bg-blue-200');
        }
    }
});

// 人数入力のボタンが押下されたら
$(".people_button").on("click",function(){
    // 押下されたボタンの値を足す
    $('#people_input').val(Number($('#people_input').val()) + Number($(this).text()));
});

// 人数入力のクリアボタンが押下されたら
$("#people_clear").on("click",function(){
    // クリアする
    $('#people_input').val(1);
});

// 稼働時間入力のボタンが押下されたら
$(".working_time_button").on("click",function(){
    // 押下されたボタンの値を足す
    $('#working_time_input').val((Number($('#working_time_input').val()) + Number($(this).text())).toFixed(2));
});

// 稼働時間入力のクリアボタンが押下されたら
$("#working_time_clear").on("click",function(){
    // クリアする
    var default_time = 0;
    $('#working_time_input').val(default_time.toFixed(2));
});

// 入力完了ボタンが押下されたら
$("#create_enter").on("click",function(){
    try {
        // 日付が正しく入力されているかチェック
        var date = new Date($('#date').val());
        if(isNaN(date.getDate())){
            throw new Error('日付が正しく入力されていません。');
        }
        // 派遣会社が選択されているか
        if($('[name="temporary_company_id"]').length > 0 && $('[name="temporary_company_id"]:checked').length == 0){
            throw new Error('派遣会社が選択されていません。');
        }
        // 荷主会社が選択されているか
        if($('[name="customer_id"]').length > 0 && $('[name="customer_id"]:checked').length == 0){
            throw new Error('荷主が選択されていません。');
        }
        // 人数が入力されているかチェック
        if(Number($('#people_input').val()) < 1){
            throw new Error('人数が入力されていません。');
        }
        // 稼働時間が入力されているかチェック
        if(Number($('#working_time_input').val()) < 0.25){
            throw new Error('稼働時間が入力されていません。');
        }
        // 0.25単位で稼働時間が入力されているかチェック
        if(Number($('#working_time_input').val()) % 0.25 != 0){
            throw new Error('稼働時間が0.25単位で入力されていません。');
        }
        // 処理を実行するか確認
        const result = window.confirm("派遣利用入力を実行しますか？");
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true) {
            $("#temporary_use_create_enter_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});

// 削除が押下されたら
$(".temporary_use_delete_enter").on("click",function(){
    // アンダーバーで分割
    var split = $(this).attr('id').split('_');
    // 削除対象の派遣利用IDを取得
    const temporary_use_id = split[split.length - 1];
    // 処理を実行するか確認
    const result = window.confirm("削除を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $("#temporary_use_delete_form_" + temporary_use_id).submit();
    }
});