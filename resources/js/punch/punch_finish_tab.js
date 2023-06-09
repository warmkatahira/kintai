const tabs = document.getElementsByClassName('tab_menu');
  for(let i = 0; i < tabs.length; i++){
    tabs[i].addEventListener('click',tabSwitch);
  }
  function tabSwitch(){
    document.getElementsByClassName('active')[0].classList.remove('active');
    this.classList.add('active');
    document.getElementsByClassName('show')[0].classList.remove('show');
    const arrayTabs = Array.prototype.slice.call(tabs);
    const index = arrayTabs.indexOf(this);
    document.getElementsByClassName('tab_detail_wrap')[index].classList.add('show');
};