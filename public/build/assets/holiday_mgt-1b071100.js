$(".select_file input[type=file]").on("change",function(){window.confirm("休日アップロードを実行しますか？")==!0&&$("#holiday_upload_form").submit(),$("#select_file").val(null)});
