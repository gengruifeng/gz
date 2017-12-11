function hashchange() {
    u = location.href.split('#');
    var uid = $('.'+u[1]).attr('data-text');
    $('.question').removeClass('onClick');
    $('.answer').removeClass('onClick');
    $('.article').removeClass('onClick');
    $('.collect').removeClass('onClick');
    $('.follow').removeClass('onClick');
    $('#contentShowquestion').addClass('hide');
    $('#contentShowanswer').addClass('hide');
    $('#contentShowarticle').addClass('hide');
    $('#contentShowcollect').addClass('hide');
    $('#contentShowfollow').addClass('hide');
    if(u[1] != undefined &&　u[1] == 'question'){
        $('#contentShowquestion').removeClass('hide');
        $('#contentShowquestion').pagedList({
            serverCall: '/profile/question?uid='+uid,
            kwargs: QueryString,
            hiddenClass: 'hidden'
        });
        $('.'+u[1]).addClass('onClick');
    }else if(u[1] != undefined &&　u[1] == 'answer'){
        $('#contentShowanswer').removeClass('hide');
        $('#contentShowanswer').pagedList({
            serverCall: '/profile/answer?uid='+uid,
            kwargs: QueryString,
            hiddenClass: 'hidden'
        });
        $('.'+u[1]).addClass('onClick');
    }else if(u[1] != undefined &&　u[1] == 'article'){
        $('#contentShowarticle').removeClass('hide');
        $('#contentShowarticle').pagedList({
            serverCall: '/profile/article?uid='+uid,
            kwargs: QueryString,
            hiddenClass: 'hidden'
        });
        $('.'+u[1]).addClass('onClick');
    }else if(u[1] != undefined &&　u[1] == 'collect'){
        $('#contentShowcollect').removeClass('hide');
        $('#contentShowcollect').pagedList({
            serverCall: '/profile/collect?uid='+uid,
            kwargs: QueryString,
            hiddenClass: 'hidden'
        });
        $('.'+u[1]).addClass('onClick');
    }else if(u[1] != undefined &&　u[1] == 'follow'){
        $('#contentShowfollow').removeClass('hide');
        $('#contentShowfollow').pagedList({
            serverCall: '/profile/follow?uid='+uid,
            kwargs: QueryString,
            hiddenClass: 'hidden'
        });
        $('.'+u[1]).addClass('onClick');
    }else{
        var uid = $('.question').attr('data-text');
        $('#contentShowquestion').removeClass('hide');
        $('#contentShowquestion').pagedList({
            serverCall: '/profile/question?uid='+uid,
            kwargs: QueryString,
            hiddenClass: 'hidden'
        });
        $('.question').addClass('onClick');
    }
}

$(window).bind('hashchange', function() {
    hashchange();
});



$(function(){
    foucus_1('#questioonTitle');
    foucus_1('#questionContent');
    foucus_1('#add_tags');
    foucus_1('#privateLetterMsg');
    foucus_1('#privateMsgId');
    $('#myCenter_sendLetter').on('click', function() {
        isLogin("sendLetter()",2);
    });

    $('#myCenter_quiz').on('click',function(){
        isLogin("quiz()",2);
    });
    $('#call_oof').on('click', function() {
        $('#maskLayer').fadeOut();
    });
    $('#call_oof_1').on('click', function() {
        $('#maskLayer_1').fadeOut();
    });
    hashchange();
});
function sendLetter() {
    showOverlay("#maskLayer");
    $('#maskLayer').fadeIn();
}

function quiz() {
    showOverlay("#maskLayer_1");
    $('#maskLayer_1').fadeIn();
}