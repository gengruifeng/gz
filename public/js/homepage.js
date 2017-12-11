// 滚动头部加背景 只首页
$(window).on('scroll resize',function(){
    if ($(window).scrollTop()>100){  
        $("header").css('background','#222');
    }else{  
       $("header").css('background','none');
    }  
});