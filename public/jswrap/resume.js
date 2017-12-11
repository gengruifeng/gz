(function (doc, win) {
    var docEl = doc.documentElement,
            resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
            recalc = function () {
                var clientWidth = docEl.clientWidth;
                if (!clientWidth) return;
                docEl.style.fontSize = 20 * (clientWidth / 320) + 'px';
                scaleNumber =20 * (clientWidth / 320)/53.5;
                if(scaleNumber>.91){
                    scaleNumber =.91;
                }
                $('.your-Tm').css({
                    width: '210mm',
                    transform: 'scale('+scaleNumber+')',
                    'transform-origin': '0px 0px 0px'
                });
                var TmHeight = $('.your-Tm ').height();
                $('.your-Tm-wrap').height(TmHeight*scaleNumber).css('overflow','hidden');

            };
    if (!doc.addEventListener) return;
    win.addEventListener(resizeEvt, recalc, false);
    doc.addEventListener('DOMContentLoaded', recalc, false);
})(document, window);