document.getElementById("working_time_input_modal");const r=document.getElementById("input_customer_id"),v=document.getElementById("input_customer_name"),h=document.getElementById("input_working_time"),E=document.getElementById("input_working_time_info"),a=document.getElementById("input_time_left");document.getElementById("input_time_left_modal");const N=document.getElementById("rest_time"),m=document.getElementById("org_rest_time"),y=document.getElementById("working_time"),p=document.getElementById("org_working_time");document.getElementById("punch_confirm_modal");document.getElementById("punch_enter_form");$(".working_time_input_modal_open").on("click",function(){$("#working_time_input_modal").removeClass("hidden"),$("#input_customer_id").val($(this).val()),$("#input_customer_name").html($(this).html()),$("#input_working_time").html("0.00"),$("#input_time_left_modal").html(Number($("#input_time_left").html()).toFixed(2))});$(".working_time_input_modal_close").on("click",function(){$("#working_time_input_modal").addClass("hidden")});$(".input_time").on("click",function(){$("#input_working_time").html((Number($(this).html())+Number($("#input_working_time").html())).toFixed(2))});$("#input_working_time_clear").on("click",function(){$("#input_working_time").html("0.00")});$("[id=all_input]").on("click",function(){$("#input_working_time").html($("#input_time_left_modal").html())});$("#working_time_input_enter").on("click",function(){try{if($("#working_time_input_"+$("#input_customer_id").val()).length!==0)throw new Error("既に存在する荷主です。");if(Number($("#input_working_time").html())==0)throw new Error("時間が入力されていません。");if(Number($("#input_time_left").html())<Number($("#input_working_time").html()))throw new Error("入力時間が稼働時間を超えています。");const t=document.createElement("button");t.id="working_time_input_"+r.value,t.classList.add("working_time_info_delete","col-span-4","py-5","text-center","bg-blue-200","text-xl","rounded-lg","cursor-pointer","working_time_input_"+r.value),t.innerHTML=v.innerHTML+"<br>"+h.innerHTML;const n=document.createElement("input");n.type="hidden",n.id="working_time_input_"+r.value+"_hidden",n.classList.add("working_time_input","working_time_input_"+r.value),n.value=h.innerHTML,n.name="working_time_input["+r.value+"]",E.append(t,n),$("#input_time_left").html((Number($("#input_time_left").html())-Number($("#input_working_time").html())).toFixed(2)),$("#working_time_input_modal").addClass("hidden")}catch(t){alert(t.message)}});$(document).on("click",".working_time_info_delete",function(){const t=document.getElementById(this.id),n=document.getElementById(this.id+"_hidden");$("#input_time_left").html((Number($("#input_time_left").html())+Number(n.value)).toFixed(2)),t.remove(),n.remove()});$(".no_rest_time_select").on("click",function(){c()});$(".rest_time_select").on("click",function(){c()});$(".add_rest_time_select").on("click",function(){c()});window.onload=function(){c()};function c(){for(var t=document.getElementsByName("no_rest_time"),n=document.getElementsByName("rest_time_select"),_=document.getElementsByName("add_rest_time"),f=document.getElementById("rest_related_select_mode"),l=0,i=0,u=0,e=0;e<t.length;e++){const g=document.getElementById(t[e].id+"_label");if(t[e].checked){g.classList.add("bg-blue-200");var k=document.getElementById(t[e].id);l=k.value}t[e].checked||g.classList.remove("bg-blue-200")}for(var e=0;e<n.length;e++){const o=document.getElementById(n[e].id+"_label");if(n[e].checked){o.classList.add("bg-blue-200");var w=document.getElementById(n[e].id);i=w.value}n[e].checked||o.classList.remove("bg-blue-200")}f.value=="no_rest"&&(i=m.value);for(var e=0;e<_.length;e++){const o=document.getElementById(_[e].id+"_label");if(_[e].checked){o.classList.add("bg-blue-200");var b=document.getElementById(_[e].id);u=b.value}_[e].checked||o.classList.remove("bg-blue-200")}N.value=Number(m.value)-Number(l)+Number(u)-(Number(m.value)-Number(i)),console.log(Number(m.value)-Number(i)),y.value=((Number(p.value)+Number(l)-Number(u)+(Number(m.value)-Number(i)))/60).toFixed(2),a.innerHTML=((Number(p.value)+Number(l)-Number(u)+(Number(m.value)-Number(i)))/60).toFixed(2);let s=document.getElementsByClassName("working_time_input");for(var d=0;d<s.length;d++)a.innerHTML=(Number(a.innerHTML)-Number(s[d].value)).toFixed(2)}$("#punch_finish_enter").on("click",function(){console.log($('[name="no_rest_time"]:checked').length);try{if(Number($("#input_time_left").html())>0)throw new Error("入力されていない稼働時間があります。");if(Number($("#input_time_left").html())<0)throw new Error(`荷主稼働時間がマイナスになっています。
時間を調整して下さい。`);if($('[name="no_rest_time"]').length>0&&$('[name="no_rest_time"]:checked').length==0)throw new Error("休憩未取得時間が選択されていません。");if($('[name="rest_time_select"]').length>0&&$('[name="rest_time_select"]:checked').length==0)throw new Error("休憩取得時間が選択されていません。");if($('[name="add_rest_time"]').length>0&&$('[name="add_rest_time"]:checked').length==0)throw new Error("追加休憩取得時間が選択されていません。");$("#punch_confirm_modal").removeClass("hidden"),$("#punch_target_employee_name").html($("#employee_name").html())}catch(t){return alert(t.message),!1}});$("#punch_confirm_cancel").on("click",function(){$("#punch_confirm_modal").addClass("hidden")});$("#punch_confirm_enter").on("click",function(){$("#punch_enter_form").submit()});