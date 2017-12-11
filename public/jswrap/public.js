/**
 * Created by viviannow on 08/11/2016.
 */
//回到顶部
$(window).on('resize scroll',function(){
    if($(window).scrollTop()>50){
        $('a#btn-back-top').show();
    }else{
        $('a#btn-back-top').hide();
    }
    // console.log(1)
});
$('a#btn-back-top').click(function () {
    var speed=200;//滑动的速度
    $('body,html').animate({ scrollTop: 0 }, speed);
    return false;
});