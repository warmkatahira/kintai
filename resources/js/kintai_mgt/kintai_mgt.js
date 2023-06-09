import get_checkbox from '../checkbox';

// チェックアイコン(thタグ)を押下したら
$('#all_check').on("click",function(){
    // チェックボックス要素関連の情報を取得
    const [chk, count, all] = get_checkbox();
    // チェックボックスがONの要素数と取得した全ての要素数が同じかどうかでONにするかOFFにするか判定
    if (count == all) {
        for(let i = 0; i < chk.length; i++) {
            // OFF
            chk[i].checked = false
        }
    } else {
        for(let i = 0; i < chk.length; i++) {
            // ON
            chk[i].checked = true
        }
    }
});

// 拠点確認ボタンが押されたら
$('#base_check_enter').on("click",function(){
    try {
        // チェックボックス要素関連の情報を取得
        const [chk, count, all] = get_checkbox();
        // 対象が1つ以上選択されているか
        if (count == 0) {
            throw new Error('勤怠が選択されていません。');
        }
        // 処理を実行するか確認
        const result = window.confirm(count + "件の勤怠の拠点確認を実行しますか？");
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true) {
            $("#base_check_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});