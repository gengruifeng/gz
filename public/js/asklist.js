
//滚动加载开始
//问答页面最新问题切换开始
$(function() {
    var cache = {};

    $('.qa').on('click', function() {
        var index = $(this).index();
        $(this).addClass('onClick').siblings().removeClass('onClick');
        $('.qa_ul').eq(index).removeClass('dispaly').siblings().addClass('dispaly');
    });
});
//问答页面最新问题切换结束
//人物名片开始

