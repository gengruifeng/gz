"undefined" == typeof WeakMap && !function() {
    var t = Object.defineProperty,
        e = Date.now() % 1e9,
        n = function() {
            this.name = "__st" + (1e9 * Math.random() >>> 0) + (e++ + "__");
        };

    n.prototype = {
        set: function(e, n) {
            var r = e[this.name];
            return r && r[0] === e ? r[1] = n : t(e, this.name, {
                value: [e, n],
                writable: !0
            }), this
        },
        get: function(t) {
            var e;
            return (e = t[this.name]) && e[0] === t ? e[1] : void 0;
        },
        "delete": function(t) {
            var e = t[this.name];
            return e && e[0] === t ? (e[0] = e[1] = void 0, !0) : !1;
        },
        has: function(t) {
            var e = t[this.name];
            return e ? e[0] === t : !1;
        }
    },
    window.WeakMap = n;
}();

var QueryString = function () {
    var query,
        search,
        kwargs,
        pairs,
        arr;

    query = {},
    search = window.location.search.substring(1);
    kwargs = search.split('&');

    for (var i = 0, l = kwargs.length; i < l; i++) {
        pairs = kwargs[i].split('=');

        if (typeof pairs[1] === 'undefined') {
            continue
        }

        if (!query.hasOwnProperty(pairs[0])) {
            query[pairs[0]] = decodeURIComponent(pairs[1]);
        } else if (typeof query[pairs[0]] === 'string') {
            arr = [pairs[0], decodeURIComponent(pairs[1])];
            query[pairs[0]] = arr;
        } else {
            query[pairs[0]].push(decodeURIComponent(pairs[1]));
        }
    }

    return query;
}();

(function () {
    var CreateLoading = function (settings) {
        var container = document.createElement('div');

        container.className = 'loading-dots';

        var f = document.createElement('div'),
            s = document.createElement('div'),
            t = document.createElement('div');

        f.className = 'dot first',
        s.className = 'dot second',
        t.className = 'dot third';

        container.appendChild(f);
        container.appendChild(s);
        container.appendChild(t);

        return container;
    };

    this.CreateLoading = CreateLoading;
}).call(this);
