(function () {
    var PagedList = function ($container, options) {
        this.lastCheckTime = 0;
        this.rateLimitMS = 245;
        this.throttle = null;
        this.initialCount = 0;
        this.totalCount = 0;
        this.bufferCount = 5;
        this.increaseCount = 0;
        this.currentlyFetching = false;
        this.waitingForUpdates = false;
        this.lastGetMoreSucceeded = false;
        this.pagingDone = false;

        this.container = $container;
        this.list = this.container.children(':first');
        this.options = options || {};

        this.initialize();
    };

    PagedList.prototype.initialize = function () {
        var self = this;

        this.initializeCounts();
        this.loadSentinel();

        var t = function () {
            setTimeout(self.checkButtonPosition.bind(self), 150);
        }

        this.mousemoveHandler = t;
        this.keypressHandler = t;
        this.scrollHandler = t;

        document.addEventListener("mousemove", this.mousemoveHandler);
        document.addEventListener("keypress", this.keypressHandler);
        document.addEventListener("scroll", this.scrollHandler);

        this.checkButtonPosition();
    };

    PagedList.prototype.destroy = function () {
        document.removeEventListener("mousemove", this.mousemoveHandler);
        document.removeEventListener("keypress", this.keypressHandler);
        document.removeEventListener("scroll", this.scrollHandler);
    };

    PagedList.prototype.checkButtonPosition = function () {
        var t = +new Date();

        if (t < this.lastCheckTime + this.rateLimitMS) {
            clearTimeout(this.throttle);
            this.throttle = setTimeout($.proxy(this.checkButtonPosition, this), this.rateLimitMS);

            return;
        }

        this.lastCheckTime = t;

        var winHeight = $(window).height(),
            winScrollTop = $(window).scrollTop(),
            coordinate = this.sentinel.offset(),
            r = winHeight + winScrollTop;

        if (r >= coordinate.top - winHeight) {
            this.onMoreButtonClick();
        }

        if (this.currentlyFetching || this.waitingForUpdates) {
            this.showLoading();
        }
    };

    PagedList.prototype.loadSentinel = function () {
        var e = this,
            sentinel = document.createElement('div'),
            loading = CreateLoading();

        sentinel.className = this.options.sentinelClass;

        this.loading = $(loading);
        this.sentinel = $(sentinel);

        this.container.append(this.loading);
        this.container.append(this.sentinel);
    };

    PagedList.prototype.maybeGetMore = function () {
        if (this.currentlyFetching || this.waitingForUpdates) {
            return false;
        }

        this.increaseCount++;

        this.options.kwargs[this.options.increaseKey] = this.increaseCount;

        this.getMore();

        return true;
    };

    PagedList.prototype.getMore = function () {
        this.currentlyFetching = true;
        this.waitingForUpdates = true;
        $.get(
            this.options.serverCall,
            this.options.kwargs,
            $.proxy(this.onGetMoreSuccess, this),
            'html'
        ).error($.proxy(this.onGetMoreError, this));
    };

    PagedList.prototype.onGetMoreSuccess = function (data) {
        this.lastGetMoreSucceeded = true;
        this.currentlyFetching = false;

        this.list.append(data);

        this.perceivedAction();
    };

    PagedList.prototype.onGetMoreError = function () {
        var e = this;

        this.lastGetMoreSucceeded = false;
        this.currentlyFetching = false;

        setTimeout(function () {
            e.waitingForUpdates = false;
            e.hideLoading();
        }, 2e3);

        this.destroy();
    };

    PagedList.prototype.perceivedAction = function () {
        this.hideLoading();
        this.waitingForUpdates = false;
    };

    PagedList.prototype.onMoreButtonClick = function () {
        var i = this.maybeGetMore();

        if (i) {
            this.showLoading();
        }

        return false;
    };

    PagedList.prototype.showLoading = function () {
        this.loading.removeClass(this.options.hiddenClass);
    };

    PagedList.prototype.hideLoading = function () {
        this.loading.addClass(this.options.hiddenClass);
    };

    PagedList.prototype.initializeCounts = function () {
        var c = this.container.children();

        this.totalCount = c.length;
    };

    $.fn.pagedList = function (options) {
        var instance = this.data('paged_list');

        if (typeof instance !== 'undefined') {
            return instance;
        }

        options = options || {};

        this.each(function () {
            instance = new PagedList($(this), $.extend(true, {}, $.fn.pagedList.defaults, options));

            $(this).data('paged_list', instance);
        });

        return instance;
    };

    $.fn.pagedList.defaults = {
        serverCall: 'paged.html',
        sentinelClass: 'sentinel',
        increaseKey: 'page',
        kwargs: {},
        hiddenClass: 'u-hide'
    };
}).call(this);
