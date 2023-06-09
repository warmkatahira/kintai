export default function get_checkbox(){
    // name属性がchk[]の要素を取得
    const chk = document.getElementsByName("chk[]");
    // カウント用の変数をセット
    let count = 0;
    let all = 0;
    // 取得した要素の分だけループ処理
    for (let i = 0; i < chk.length; i++) {
        // 要素の数をカウントしている
        all++;
        // チェックボックスがONになっている要素をカウント
        if (chk[i].checked) {
            count++;
        }
    }
    return [chk, count, all];
}