(function () {
    var HoverCard = function ($linkElem, settings) {
        this.sd = 350;
        this.hd = 350;
        this.st = null;
        this.ht = null;
        this.loaded = false;
        this.loading = false;
        this.failed = false;
        this.shown = false;
        this.cancelHover = false;
        this.cardPrepended = false;
        this.$linkElem = $linkElem;
        this.settings = settings;

        var $cardElem = $('<div>').addClass(this.settings.cardClass);
        this.$cardElem = $cardElem;
        this.loadingDots = $(CreateLoading());

        this.initialize();
    };

    HoverCard.prototype.initialize = function () {
        this.showSlow();

        this.$linkElem.hover(this.showSlow.bind(this), function () {
            this.hideSlow();
            this.cancelHover = false;
        }.bind(this));

        this.$cardElem.hover(this.showSlow.bind(this), this.hideSlow.bind(this));
    };

    HoverCard.prototype.hideSlow = function () {
        clearTimeout(this.st);
        this.ht = setTimeout(this.hide.bind(this), this.hd);
    };

    HoverCard.prototype.hide = function () {
        if (!this.shown) {
            return;
        }

        this.cancelHover = true;
        this.shown = false;
        this.$cardElem.addClass("animate-out");

        setTimeout(function () {
            if (this.shown) {
                return;
            }

            this.$cardElem.removeClass("animate-out");
            this.$cardElem.addClass(this.settings.hiddenClass);
        }.bind(this), 150);
    };

    HoverCard.prototype.showSlow = function () {
        clearTimeout(this.ht);
        this.st = setTimeout(this.show.bind(this), this.sd);
        this.loadCard();
    };

    HoverCard.prototype.show = function () {
        if (this.shown || this.cancelHover) {
            return;
        }

        this.shown = true;
        this.prependCard();
        this.$cardElem.removeClass(this.settings.hiddenClass);
        this.dropPosition();
    };

    HoverCard.prototype.prependCard = function () {
        if (this.cardPrepended) {
            return;
        }

        this.$cardElem.detach().prependTo($("body"));
        this.cardPrepended = true;
    };

    HoverCard.prototype.loadCard = function () {
        if (this.loaded || this.loading || this.failed) {
            return;
        }

        this.loading = true;
        
        this.loadingDots.appendTo(this.$cardElem);

        $.get(
            this.$linkElem.data(this.settings.cardUrl),
            {},
            $.proxy(function (data) {
                this.loaded = true;
                this.loadingDots.detach();
                this.$cardElem.append(data);
            }, this),
            'html'
        ).fail($.proxy(function () {
            this.failed = true;
        }, this)
        ).always($.proxy(function () {
            this.loading = false;
        }, this));
    };

    HoverCard.prototype.dropPosition = function () {
        var win = $(window),
            linkOffset = this.$linkElem.offset(),
            linkOuterHeight = this.$linkElem.outerHeight(),
            winHeight = win.height(),
            o = 275,
            r = linkOffset.top - win.scrollTop(),
            a = winHeight - r < o,
            cardOuterHeight = this.$cardElem.outerHeight();

        this.$cardElem.removeAttr("style");

        if (a) {
            this.$cardElem.removeClass(this.settings.belowClass);
            this.$cardElem.addClass(this.settings.aboveClass);
            this.$cardElem.css('bottom', winHeight - linkOffset.top + this.settings.cardOffset);
        } else {
            this.$cardElem.removeClass(this.settings.aboveClass);
            this.$cardElem.addClass(this.settings.belowClass);
            this.$cardElem.css('top', linkOffset.top + linkOuterHeight + this.settings.cardOffset);
        }

        this.$cardElem.css('left', linkOffset.left);
    };

    $.fn.hoverCard = function (settings) {
        var instance = this.data('hoverCard');

        if (typeof instance !== 'undefined') {
            return instance;
        }

        settings = settings || {};

        this.each(function () {
            instance = new HoverCard($(this), $.extend(true, {}, $.fn.hoverCard.defaults, settings));
            $(this).data('hoverCard', instance);
        });

        return instance;
    };

    $.fn.hoverCard.defaults = {
        cardUrl: 'card-url',
        cardClass: 'hover-card',
        aboveClass: 'hover-card-above',
        belowClass: 'hover-card-below',
        hiddenClass: 'u-hide',
        cardOffset: 11
    };
}).call(this);
