@extends('layouts.layout')

@section('head')
    <title>有价值的职场经验文章-工作网</title>
    <meta name="description" content="工作网-最专业的大学生职业成长平台。为大学生提供最专业的职场经验分享。"/>
    <meta name="keywords" content="工作网,求职简历,笔试面试,资深导师,三方协议,咨询,快消,互联网,四大,offer保证,实习,全职,企业,名企,500强,学员,一对一,宣讲会,简历,财经,金融,笔试,面试,案例,群面"/>
@endsection


@section('content')
    <!--文章列表开始-->
    <form class="articleList" action="">
        <div>
            <div>
                <p>全部文章</p>
            </div>
            <div id="paged">
                <ul>

                </ul>

            </div>
            <div>

            </div>
        </div>
        <div>
            <div><h3>热门标签</h3></div>
            <div>
                @foreach ($tags as $tag)
                <a href="javascript:void(0)">{{ $tag->name }}</a>
                @endforeach
            </div>
        </div>
        {{ csrf_field() }}
        <input class="_token" type="text" />
    </form>
    <a href="javascript:;" id="btn-back-top"></a>
    <!--文章列表结束-->
@endsection
@section('askdateil')
    <!--名片展示开始-->
    <div id="callingCard">
    </div>
    <!--名片展示结束-->
    <!--私信遮罩层开始-->
    <div id="maskLayer" class="dispaly">

    </div>
    <!--私信遮罩层结束-->
    <!--提问遮罩层开始-->
    <div id="maskLayer_1" class="dispaly">

    </div>
    <!--提问遮罩层结束-->
@endsection
@section('stylesheets')
<link href="{{ asset('css/inform.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/selectize/selectize.default.css') }}"/>
@endsection
@section('javascripts')
    <script src="{{ asset('js/card.js') }}"></script>
    <script src="{{ asset('js/selectize.js') }}"></script>
    <script src="{{ asset('js/shared/util.js') }}"></script>
    <script src="{{ asset('js/view/paged_list.js') }}"></script>
    <script src="{{ asset('js/view/hover_menu.js') }}"></script>
    <script>
        $('#paged').pagedList({
            serverCall: 'articles/list',
            kwargs: QueryString,
            hiddenClass: 'hidden'
        });

        $(document).on('mouseenter', '.rich-avatar', function (event) {
            $(this).hoverCard({
                cardUrl: 'card-url',
                hiddenClass: 'hidden',
                cardOffset: 11
            });
        });
    </script>
<script>
var _hmt = _hmt || [];
(function() {
    var hm = document.createElement("script");
    hm.src = "//hm.baidu.com/hm.js?ec1bad026af70feb78bba2696756c485";
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hm, s);
})();
</script>
@endsection
<link rel="stylesheet" type="text/css" href="../css/vcommon.css"/>