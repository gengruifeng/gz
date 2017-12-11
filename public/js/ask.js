$('#add').click(function () {
    $('.tag-bar').prepend('<span class="topic-tag"><a class="text">' + i+ '</a><a class="close" onclick="$(this).parents(\'.topic-tag\').remove();"><i class="icon icon-delete"></i></a><input type="hidden" value="' + i + '" name="topics[]" /></span>');
});
$('#submit').click(function () {
    var tag = $('#topics').val();
    var title = $('#title').val();
    var detail = $('#detail').val();
    var token = $("input[name = '_token']").val();
    $.ajax({
        type:"post",
        url:"/ajax/questions/ask",
        async : false,
        data:{askType:2,tags:tag,_token:token,subject:title,detail:detail},
        dataType: "json"
    }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
        if(textStatus == 'error'){
            dialogcom_wrong(errorThrown);
        }
    })
});
$('#huati').keyup(function () {
    var i = $('#huati').val();
    var token = $("input[name = '_token']").val();
    $.ajax({
        type:"post",
        url:"/ajax/questions/taglist",
        async : false,
        data:{askType:1,tag:i,_token:token},
        dataType: "json"
    }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
        if(textStatus == 'error'){
          dialogcom_wrong(errorThrown);
        }
    })
});