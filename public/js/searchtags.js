//sphinx查询标签名
$(function() {
    $('#search_tag').selectize({
        maxItems: 5,
        create: function (input) {
            if (input.length > 6) {
                dialogcom_warn("标签长度最多为6个字符！");
                return false;
            }
            return {
                name: input
            }
        },
        valueField: 'name',
        labelField: 'name',
        searchField: 'name',

        options: [],
        render: {
            option: function (item, escape) {
                var tags = [];
                for (var i = 0, n = item.length; i < n; i++) {
                    tags.push('<span>' + escape(item[i].name) + '</span>');
                }
                return '<div>' +
                    '<span class="name">' + escape(item.name) + '</span>' +
                    '</div>';
            }
        },
        load: function (query, callback) {
            if (!query.length) return callback();
            $.ajax({
                url: '/tags/search',
                type: 'GET',
                dataType: 'json',
                data: {
                    q: query
                },
                error: function () {
                    callback();
                },
                success: function (res) {

                    callback(res);

                }
            });
        }
    });
});