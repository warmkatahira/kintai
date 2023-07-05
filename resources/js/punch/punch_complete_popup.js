const punch_finish = document.querySelector('.punch_finish');

window.addEventListener('load', () => {
    if(punch_finish !== null && !punch_finish.classList.contains('hide')){
        // 指定時間経過後に、要素の非表示とスクロール操作の禁止解除を実施
        scroll_disabled();
        setTimeout(function(){
            punch_finish.classList.add('hide');
            scroll_enabled();
        },3000);
    }
}, false );

function handle(event) {
    event.preventDefault();
}

// スクロール操作の禁止を解除
function scroll_enabled() {
    document.removeEventListener('touchmove', handle, { passive: false });
    document.removeEventListener('mousewheel', handle, { passive: false });
}

// スクロール操作の禁止を設定
function scroll_disabled() {
    document.addEventListener('touchmove', handle, { passive: false });
    document.addEventListener('mousewheel', handle, { passive: false });
}
