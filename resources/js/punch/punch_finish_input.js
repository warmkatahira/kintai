const modal = document.getElementById('working_time_input_modal');
const input_customer_id = document.getElementById('input_customer_id');
const input_customer_name = document.getElementById('input_customer_name');
const input_working_time = document.getElementById('input_working_time');
const input_working_time_info = document.getElementById('input_working_time_info');
const input_time_left = document.getElementById('input_time_left');
const input_time_left_modal = document.getElementById('input_time_left_modal');
const rest_time = document.getElementById('rest_time');
const org_rest_time = document.getElementById('org_rest_time');
const working_time = document.getElementById('working_time');
const org_working_time = document.getElementById('org_working_time');
const punch_confirm_modal = document.getElementById('punch_confirm_modal');
const punch_enter_form = document.getElementById('punch_enter_form');

// 荷主稼働時間入力モーダルを開く
$(".working_time_input_modal_open").on("click",function(){
    $('#working_time_input_modal').removeClass('hidden');
    // 入力対象の荷主情報を出力
    $('#input_customer_id').val($(this).val());
    $('#input_customer_name').html($(this).html());
    // 稼働時間の数値を初期化
    $('#input_working_time').html('0.00');
    // 残り入力時間を更新
    $('#input_time_left_modal').html(Number($('#input_time_left').html()).toFixed(2));
});

// 荷主稼働時間入力モーダルを閉じる
$(".working_time_input_modal_close").on("click",function(){
    $('#working_time_input_modal').addClass('hidden');
});

// 押下された数値をプラスする
$(".input_time").on("click",function(){
    $('#input_working_time').html((Number($(this).html()) + Number($('#input_working_time').html())).toFixed(2));
});

// 稼働時間のクリアボタンが押下されたら数値を初期化
$("#input_working_time_clear").on("click",function(){
    $('#input_working_time').html('0.00');
});

// 残り時間を全て入力ボタンが押下された場合
$("[id=all_input]").on("click",function(){
    $('#input_working_time').html($('#input_time_left_modal').html());
});

// 時間入力が押下されたら
$("#working_time_input_enter").on("click",function(){
    try {
        // 既に存在する荷主ではないかチェック
        if($('#working_time_input_' + $('#input_customer_id').val()).length !== 0) {
            throw new Error('既に存在する荷主です。');
        }
        // 時間が入力されているかチェック
        if(Number($('#input_working_time').html()) == 0){
            throw new Error('時間が入力されていません。');
        }
        // 残り入力時間より大きい時間ではないかチェック
        if(Number($('#input_time_left').html()) < Number($('#input_working_time').html())){
            throw new Error('入力時間が稼働時間を超えています。');
        }
        // 表示させる要素を作成して表示
        const working_time_input = document.createElement('button');
        working_time_input.id = 'working_time_input_' + input_customer_id.value;
        working_time_input.classList.add('working_time_info_delete', 'col-span-4', 'py-5', 'text-center', 'bg-blue-200', 'text-xl', 'rounded-lg', 'cursor-pointer', 'working_time_input_' + input_customer_id.value);
        working_time_input.innerHTML = input_customer_name.innerHTML + "<br>" + input_working_time.innerHTML;
        // 送信する要素を作成して表示
        const working_time_hidden = document.createElement('input');
        working_time_hidden.type = 'hidden';
        working_time_hidden.id = 'working_time_input_' + input_customer_id.value + '_hidden';
        working_time_hidden.classList.add('working_time_input', 'working_time_input_' + input_customer_id.value);
        working_time_hidden.value = input_working_time.innerHTML;
        working_time_hidden.name = 'working_time_input' + '[' + input_customer_id.value + ']';
        input_working_time_info.append(working_time_input, working_time_hidden);
        // 残り入力時間を更新
        $('#input_time_left').html((Number($('#input_time_left').html()) - Number($('#input_working_time').html())).toFixed(2));
        // モーダルを閉じる
        $('#working_time_input_modal').addClass('hidden');
    } catch (e) {
        alert(e.message);
    }
});

// 押下された荷主稼働時間要素を削除
$(document).on("click", ".working_time_info_delete", function () {
    const delete_target_1 = document.getElementById(this.id);
    const delete_target_2 = document.getElementById(this.id + '_hidden');
    // 残り入力時間を更新
    $('#input_time_left').html((Number($('#input_time_left').html()) + Number(delete_target_2.value)).toFixed(2));
    // 要素を削除
    delete_target_1.remove();
    delete_target_2.remove();
});

// 追加休憩取得時間が変更されたら
$(".add_rest_time_select").on("click",function(){
    rest_time_update();
});

// 画面読み込み時の処理
window.onload = function(){
    rest_time_update();
}

function rest_time_update(){
    // デフォルト休憩取得時間を取得
    //const default_rest_time = $('#default_rest_time').val();
    // 要素を取得
    var add_rest_times = document.getElementsByName("add_rest_time");
    var add_rest_time_disp = document.getElementById("add_rest_time_disp");
    // ここで初期値をセットしている
    var select_no_rest_time_value = 0;
    var select_rest_time_value = 0;
    var select_add_rest_time_value = 0;
    // 休憩取得時間の処理
    select_rest_time_value = org_rest_time.value;
    // 追加休憩取得時間の処理
    for(var i = 0; i < add_rest_times.length; i++){
        const element = document.getElementById(add_rest_times[i].id + '_label');
        if(add_rest_times[i].checked) {
            element.classList.add('bg-blue-200');
            // 選択している追加休憩時間を取得
            var select_add_rest_time = document.getElementById(add_rest_times[i].id);
            select_add_rest_time_value = select_add_rest_time.value;
        }
        if(!add_rest_times[i].checked) {
            // 非選択要素のCSSを調整
            element.classList.remove('bg-blue-200');
        }
    }
    // 休憩時間を変更
    rest_time.value = Number(org_rest_time.value);
    // 要素がある時だけ表示させる
    if (add_rest_time_disp) {
        // 追加休憩時間を変更
        add_rest_time_disp.value = Number(select_add_rest_time_value);
    }
    // 勤務時間を変更(休憩等を加味していない稼働時間 + 休憩未取得 - 追加休憩取得 - 休憩取得)
    working_time.value = ((Number(org_working_time.value) + Number(select_no_rest_time_value) - Number(select_add_rest_time_value) - Number(select_rest_time_value)) / 60).toFixed(2);
    // 残り入力時間を変更
    input_time_left.innerHTML = ((Number(org_working_time.value) + Number(select_no_rest_time_value) - Number(select_add_rest_time_value) - Number(select_rest_time_value)) / 60).toFixed(2);
    let elements = document.getElementsByClassName('working_time_input');
    for(var k = 0; k < elements.length; k++){
        input_time_left.innerHTML = (Number(input_time_left.innerHTML) - Number(elements[k].value)).toFixed(2);
    }
}

// 確定処理が押下されたら
$('#punch_finish_enter').on("click",function(){
    console.log($('[name="no_rest_time"]:checked').length);
    try {
        // 残り入力時間が0以外だったら処理を中断
        if(Number($('#input_time_left').html()) > 0){
            throw new Error('入力されていない稼働時間があります。');
        }
        if(Number($('#input_time_left').html()) < 0){
            throw new Error('荷主稼働時間がマイナスになっています。\n時間を調整して下さい。');
        }
        if($('[name="no_rest_time"]').length > 0 && $('[name="no_rest_time"]:checked').length == 0){
            throw new Error('休憩未取得時間が選択されていません。');
        }
        if($('[name="rest_time_select"]').length > 0 && $('[name="rest_time_select"]:checked').length == 0){
            throw new Error('休憩取得時間が選択されていません。');
        }
        if($('[name="add_rest_time"]').length > 0 && $('[name="add_rest_time"]:checked').length == 0){
            throw new Error('追加休憩取得時間が選択されていません。');
        }
        // モーダルを表示
        $('#punch_confirm_modal').removeClass('hidden');
        // 従業員名を出力
        $('#punch_target_employee_name').html($('#employee_name').html());
    } catch (e) {
        alert(e.message);
        return false;
    }
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

// 休憩取得時間の確認で「はい」が押下された場合
$('#rest_time_check_yes').on("click",function(){
    // デフォルト休憩取得時間を取得
    //const default_rest_time = $('#default_rest_time').val();
    // 稼働時間を取得
    const working_time = $('#org_working_time').val();
    // 休憩取得時間の要素を追加
    $('#rest_time_div').append(`
        <input type="radio" name="rest_time_select" id="${org_rest_time.value}" value="${org_rest_time.value}" class="rest_time_select hidden" checked>
        <label for="${org_rest_time.value}" class="cursor-pointer flex flex-col w-full max-w-lg mx-auto text-center border-2 rounded-lg border-gray-900 p-2 text-2xl bg-blue-200">${(org_rest_time.value / 60).toFixed(2)}時間</label>
    `);
    // 休憩取得時間を更新
    //$('#rest_time').val(default_rest_time);
    /* // 稼働時間を更新
    $('#working_time').val(working_time / 60);
    // 残り入力時間を更新
    $('#input_time_left').html(working_time / 60); */
    // モーダルを非表示
    $('#rest_time_check_modal').addClass('hidden');
});

// 休憩取得時間の確認で「いいえ」が押下された場合
$('#rest_time_check_no').on("click",function(){
    // モーダルを非表示
    $('#rest_time_check_modal').addClass('hidden');
    // モーダルを表示
    $('#rest_time_change_modal').removeClass('hidden');
    // 休憩取得時間の更新
    rest_time_select_update();
});

// 休憩取得時間が選択されたら
$('.rest_time_select').on("click",function(){
    rest_time_select_update();
});

// 休憩取得時間の更新
function rest_time_select_update(){
    // 注意喚起の文言を非表示
    $('#law_violated_warning').addClass('hidden');
    let rest_times = document.getElementsByName("rest_time_select");
    // 休憩取得時間の処理
    for(var i = 0; i < rest_times.length; i++){
        const element = document.getElementById(rest_times[i].id + '_label');
        if(rest_times[i].checked) {
            element.classList.add('bg-blue-200');
            // 選択している休憩時間を取得
            var select_rest_time = document.getElementById(rest_times[i].id);
            // 法令休憩取得時間よりも変更後の休憩取得時間が小さい場合
            if($('#law_rest_time').val() > select_rest_time.value){
                // 注意喚起の文言を表示
                $('#law_violated_warning').removeClass('hidden');
            }
        }
        if(!rest_times[i].checked) {
            // 非選択要素のCSSを調整
            element.classList.remove('bg-blue-200');
        }
    }
}

// 休憩取得時間の変更で「はい」が押下された場合
$('#rest_time_change_yes').on("click",function(){
    try {
        if($('[name="rest_time_select"]').length > 0 && $('[name="rest_time_select"]:checked').length == 0){
            throw new Error('休憩取得時間が選択されていません。');
        }
        let rest_times = document.getElementsByName("rest_time_select");
        // 選択された休憩取得時間を格納する変数を初期化
        let select_rest_time_value = 0;
        // 休憩取得時間の処理
        for(var i = 0; i < rest_times.length; i++){
            const element = document.getElementById(rest_times[i].id + '_label');
            if(rest_times[i].checked) {
                // 選択している休憩時間を取得
                var select_rest_time = document.getElementById(rest_times[i].id);
                select_rest_time_value = select_rest_time.value;
            }
        }
        // 休憩取得時間の要素を追加
        $('#rest_time_div').append(`
            <input type="radio" name="rest_time_select" id="${select_rest_time_value}" value="${select_rest_time_value}" class="rest_time_select hidden" checked>
            <label for="${select_rest_time_value}" class="cursor-pointer flex flex-col w-full max-w-lg mx-auto text-center border-2 rounded-lg border-gray-900 p-2 text-2xl bg-blue-200">${(select_rest_time_value / 60).toFixed(2)}時間</label>
        `);
        // デフォルト休憩取得時間よりも変更後の休憩取得時間が小さい場合
        if($('#org_rest_time').val() > select_rest_time_value){
            // 所長承認のフラグを1にする
            $('#is_chief_approvaled').val(1);
        }
        // 休憩取得時間を更新
        $('#org_rest_time').val(select_rest_time_value);
        // 休憩時間の更新
        rest_time_update();
        // 法令休憩取得時間よりも変更後の休憩取得時間が小さい場合
        if($('#law_rest_time').val() > select_rest_time_value){
            // 法令違反のフラグを1にする
            $('#is_law_violated').val(1);
        }
        // モーダルを非表示
        $('#rest_time_change_modal').addClass('hidden');
    } catch (e) {
        alert(e.message);
        return false;
    }
});

// 休憩取得時間の変更で「いいえ」が押下された場合
$('#rest_time_change_no').on("click",function(){
    // ブラウザバック
    window.history.back();
});