// 30秒毎に自動更新
$(document).ready(function() {
    let autoReload;
    function startAutoReload() {
        autoReload = setInterval(function() {
            location.reload();
        }, 10000);
    }
    // ページ読み込み時に自動更新を開始
    startAutoReload();
    // 停止ボタンがクリックされたら自動更新を停止
    $('.comment_update').click(function() {
        clearInterval(autoReload);
    });
    // 再開ボタンがクリックされたら自動更新を再開
    $('#comment_modal_close').click(function() {
        startAutoReload();
    });
});