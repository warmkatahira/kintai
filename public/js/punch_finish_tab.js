/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!************************************************!*\
  !*** ./resources/js/punch/punch_finish_tab.js ***!
  \************************************************/
var tabs = document.getElementsByClassName('tab_menu');
for (var i = 0; i < tabs.length; i++) {
  tabs[i].addEventListener('click', tabSwitch);
}
function tabSwitch() {
  document.getElementsByClassName('active')[0].classList.remove('active');
  this.classList.add('active');
  document.getElementsByClassName('show')[0].classList.remove('show');
  var arrayTabs = Array.prototype.slice.call(tabs);
  var index = arrayTabs.indexOf(this);
  document.getElementsByClassName('tab_detail_wrap')[index].classList.add('show');
}
;
/******/ })()
;