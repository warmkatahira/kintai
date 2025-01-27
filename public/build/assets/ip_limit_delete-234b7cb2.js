$(".ip_limit_delete_enter").on("click",function(){window.confirm("IPを削除しますか？")==!0&&($("#delete_ip_limit_id").val($(this).data("ip-limit-id")),$("#ip_limit_delete_form").submit())});
