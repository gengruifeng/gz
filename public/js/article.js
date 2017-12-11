/**
 * Article JS
 */
var url = window.location.protocol+'//'+window.location.host;
$(function() {
    var hasMore = true,
        x = $(".article > div:nth-child(3) > div:nth-child(1) > p"),
        just = false;

    // function loadComment() {
    //     if (hasMore) {
    //         $.ajax({
    //             url: "/ajax/articles/:article_id/comments".replace(":article_id", $("input[name=article_id]").val()),
    //             success: function(data, status, xhr) {
    //                 var i = 0,
    //                     l = data.length,
    //                     s = '';
    //
    //                 if (0 < l) {
    //                     var occupation = '';
    //
    //                     for (; i < l; i++) {
    //                         occupation = '';
    //                         switch (data[i].author.occupation) {
    //                             case 1:
    //                                 occupation = occupation
    //                                 + '<span>' + (data[i].author.education ? data[i].author.education.school : '') + '</span>'
    //                                 + '<span>' + (data[i].author.education ? data[i].author.education.department : '') + '</span>';
    //                                 break;
    //
    //                             case 2:
    //                                 occupation = occupation
    //                                 + '<span>' + (data[i].author.work ? data[i].author.work.company : '') + '</span>'
    //                                 + '<span>' + (data[i].author.work ? data[i].author.work.position : '') + '</span>';
    //                                 break;
    //
    //                             default:
    //                                 occupation = occupation
    //                                 + '<span></span>'
    //                                 + '<span></span>';
    //                                 break;
    //                         }
    //
    //                         s = s
    //                         + '<li>'
    //                         + '<div><img alt="' + data[i].author.display_name + '" class="rich-avatar"  data-card-url="/users/card/'+data[i].author.id+'" src='+url+'/avatars/60/' + data[i].author.avatar + ' /></div>'
    //                         + '<div>'
    //                         + '<div>'
    //                         + '<a href="/profile/'  + data[i].author.id + '" class="rich-avatar"  data-card-url="/users/card/'+data[i].author.id+'"  data-text="'+data[i].author.id+'" >' + data[i].author.display_name + '</a>'
    //                         + ( data[i].uid == data[i].author.id ? '<a href="/ajax/articles/' + data[i].article_id + '/comments/' + data[i].id + '/destroy" class="delete">删除</a>' : '<a href="javascript:void(0)"></a>')
    //                         + (window.loggedIn && window.uid !== data[i].author.id ? '<a href="javascript:void(0)" class="reply" data-mention-name="' + data[i].author.display_name + '">回复</a>' : '<a href="javascript:void(0)"></a>')
    //                         + '</div>'
    //                         + '<div>'
    //                         + occupation
    //                         + '<span>' + data[i].updated_at + '</span>'
    //                         + '</div>'
    //                         + '<div>' + data[i].content + '</div>'
    //                         + '</div>'
    //                         + '</li>';
    //                     }
    //
    //                     $(".article-ping-content ul").append(s);
    //                 }
    //
    //                 if (0 === l) {
    //                     load.html('<p>暂无评论</p>');
    //                 }
    //             },
    //             error: function(data, status, error) {
    //                 dialogcom_wrong('加载失败');
    //             }
    //         });
    //     }
    // }
    //
    // // Load Comment at first
    // loadComment();



    // Delete comment
    $(".article-ping-content ul").on("click", "li a.delete", function(e) {
        e.preventDefault();
        var c = $(this);
        $("#dialog").dialog({
            modal:true,
            dialogClass: "no-close",
            buttons: [{
                text: "取消",
                click: function() {
                    $(this).dialog("close");
                }
            },
                {
                    text: "确定",
                    click: function() {
                        $(this).dialog("close");
                        var datahref = c.attr("href");
                        $.ajax({
                            url: datahref,
                            method: "POST",
                            data: {"_token": $("input[name=_token]").val()},
                            success: function(data, status, xhr) {
                                c.parent().parent().parent().remove();
                                dialogcom_yes("删除成功");
                            },
                            error: function(xhr, status, error) {
                                dialogcom_warn(xhr.responseJSON.description);
                            }
                        });
                    }
                }
            ]
        });
    });

    // Reply comment
    $(".article-ping-content ul").on("click", "li a.reply", function(e) {
        e.preventDefault();

        editor.setValue('@' + $(this).attr('data-mention-name') + '&nbsp');
        editor.focus();
    });

//window.loggedIn && window.uid

    function justHandle(response, status, xhr, form) {
        just = true;
        var occupation = '';
        switch (response.author.occupation) {
            case 1:
                occupation = occupation
                + '<span>' + (response.author.education ? response.author.education.school : '') + '</span>'
                + '<span>' + (response.author.education ? response.author.education.department : '') + '</span>';
                break;

            case 2:
                occupation = occupation
                + '<span>' + (response.author.work ? response.author.work.company : '') + '</span>'
                + '<span>' + (response.author.work ? response.author.work.position : '') + '</span>';
                break;

            default:
                occupation = occupation
                + '<span"></span>'
                + '<span></span>';
                break;
        }
        console.log(response);

        var s = ''
            + '<li>'
            + '<div><img alt="' + response.author.display_name + '" class="rich-avatar"  data-card-url="/users/card/'+response.author.id+'" src='+url+'/avatars/60/' + response.author.avatar + ' /></div>'
            + '<div>'
            + '<div>'
            + '<a href="/profile/'  + response.author.id + '" class="rich-avatar"  data-card-url="/users/card/'+response.author.id+'">' + response.author.display_name + '</a>'
            + (response.uid=== response.author.id ? '<a href="/ajax/articles/' + response.article_id + '/comments/' + response.id + '/destroy" class="delete">删除</a>' : '<a href="javascript:void(0)"></a>')
            + (window.loggedIn && window.uid !== response.author.id ? '<a href="javascript:void(0)" class="reply" data-mention-name="' + response.author.display_name + '">回复</a>' : '<a href="javascript:void(0)"></a>')
            + '</div>'
            + '<div>' + occupation
            + '<span>' + response.updated_at + '</span>'
            + '</div>'
            + '<div>' + response.content + '</div>'
            + '</div>'
            + '</li>';

        if (/^\s+$/.test($(".article-ping-content ul").html())) {
            $(".article-ping-content ul").html(s);
        } else {
            $(".article-ping-content > ul li:nth-child(1)").before(s);
        }

        var comment_count = parseInt($("#comment-count").html());
        $("#comment-count").html(++comment_count);

        // Clear content in the editor
        $("#content").val('');
    }

	function errorHandle(response, status, xhr, form) {
        if(401 === response.status){
            dialoglogin();
            return false;
        }
        if(403=== response.status){
            dialogcom_wrong(response.responseJSON);
            return false;
        }
        dialogcom_wrong(response.responseJSON.description);
	}

	var options = {
		success: justHandle,
		error: errorHandle
	};

	$("#comment").click(function(){
		$("#comment_form").ajaxSubmit(options);
	});

});


// Vote up or Star the article
var that_vote;
$("#voteupurl").on("click", function() {
    that_vote=$(this);
    isLogin('voteupurl(that_vote)',2);
});
function voteupurl(that_vote) {
    var count = $("#voteupurl").find("span:first-child");
    $.ajax({
        url: $("#voteupurl").attr("data-url"),
        method: "POST",
        data: {"_token": $("input[name=_token]").val()},
        success: function(data, status, xhr) {
            count.text(parseInt(count.text()) + 1);
            that_vote.addClass('onClick');
        },
        error: function(xhr, status, error) {
            if(401 === xhr.status){
                dialoglogin();
                return false;
            }
            if(403=== xhr.status){
                dialogcom_wrong(xhr.responseJSON);
                return false;
            }
            dialogcom_wrong(xhr.responseJSON.description);
        }
    });
}
var that_star;
$("#staredurl").on("click", function() {
    that_star=$(this);
    isLogin('staredurl(that_star)',2);
});
function staredurl() {

    var count = $("#staredurl").find("span:first-child");

    $.ajax({
        url: $("#staredurl").attr("data-url"),
        method: "POST",
        data: {"_token": $("input[name=_token]").val()},
        success: function(data, status, xhr) {
            count.text(parseInt(count.text()) + 1);
            that_star.addClass('onClick');
        },
        error: function(xhr, status, error) {
            if(401 === xhr.status){
                dialoglogin();
                return false;
            }
            if(403=== xhr.status){
                dialogcom_wrong(xhr.responseJSON);
                return false;
            }
            dialogcom_wrong(xhr.responseJSON.description);
        }
    });
}