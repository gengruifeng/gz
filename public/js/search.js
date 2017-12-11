$(document).ready(function() {

    // the basics



    // remote
    // ------

    var bestPictures = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('subject'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: '/ajax/search?q=%QUERY',
            wildcard: '%QUERY'
        },
    });

    $('#search').typeahead({
        hint: false//阻止默认第一个到输入框
    }, {
        name: 'best-pictures',
        display: 'subject',
        // limit: 10,//最多显示10条
        source: bestPictures,
        select: function (event, ui) {
            $('.slidmsg').addClass('hide');
            $("#school_name-"+education).val(ui.item.label);
        }
    });
    $('#search').on('typeahead:selected', function (e, datum) {
       location.href="/questions/"+datum.id;
    });

});
