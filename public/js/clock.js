/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*******************************!*\
  !*** ./resources/js/clock.js ***!
  \*******************************/
var clock = function clock() {
  // 現在の日時・時刻の情報を取得
  var d = new Date();

  // 年を取得
  var year = d.getFullYear();
  // 月を取得
  var month = d.getMonth() + 1;
  // 日を取得
  var date = d.getDate();
  // 曜日を取得
  var dayNum = d.getDay();
  var weekday = ["(日)", "(月)", "(火)", "(水)", "(木)", "(金)", "(土)"];
  var day = weekday[dayNum];
  // 時を取得
  var hour = d.getHours();
  // 分を取得
  var min = d.getMinutes();
  // 秒を取得
  var sec = d.getSeconds();

  // 1桁の場合は0を足して2桁に
  month = month < 10 ? "0" + month : month;
  date = date < 10 ? "0" + date : date;
  hour = hour < 10 ? "0" + hour : hour;
  min = min < 10 ? "0" + min : min;
  sec = sec < 10 ? "0" + sec : sec;

  // 日付・時刻の文字列を作成
  var today = "".concat(year, ".").concat(month, ".").concat(date, " ").concat(day);
  var time = "".concat(hour, ":").concat(min, ":").concat(sec);

  // 文字列を出力
  document.querySelector(".clock-date").innerText = today;
  document.querySelector(".clock-time").innerText = time;
};

// 1秒ごとにclock関数を呼び出す
setInterval(clock, 1000);
/******/ })()
;