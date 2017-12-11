$(function () {
    hashchange();
})

$(window).bind('hashchange', function() {
    hashchange();
});
function hashchange() {
    $('#myTab').find('li').each(function (e) {
        $(this).removeClass('active');
    })
    $('#faq-tab-1').removeClass('active in');
    $('#faq-tab-2').removeClass('active in');
    $('#faq-tab-3').removeClass('active in');
    $('#faq-tab-4').removeClass('active in');
    $('#faq-tab-5').removeClass('active in');
    u = location.href.split('#');
    if(u[1] == 'faq-tab-1'){
        $('#myTab').find('li').eq(0).addClass('active');
        $('#faq-tab-1').addClass('active in');
        sutengseachList(1);
    }else if(u[1] == 'faq-tab-2'){
        $('#myTab').find('li').eq(1).addClass('active');
        $('#faq-tab-2').addClass('active in');
        cityseachList(1);
    }else if(u[1] == 'faq-tab-3'){
        $('#myTab').find('li').eq(2).addClass('active');
        $('#faq-tab-3').addClass('active in');
        majorseachList(1);

    }else if(u[1] == 'faq-tab-4'){
        $('#myTab').find('li').eq(3).addClass('active');
        $('#faq-tab-4').addClass('active in');
        positionseachList(1);

    }else if(u[1] == 'faq-tab-5'){
        $('#myTab').find('li').eq(4).addClass('active');
        $('#faq-tab-5').addClass('active in');
        certificateseachList(1);

    }else if(u[1] == 'faq-tab-6'){
        $('#myTab').find('li').eq(5).addClass('active');
        $('#faq-tab-6').addClass('active in');
        schoolseachList(1);
    }else{
        $('#myTab').find('li').eq(0).addClass('active');
        $('#faq-tab-1').addClass('active in');
        sutengseachList(1);
    }
}