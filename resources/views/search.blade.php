@extends('layouts.layout')

@section('head')
    <title>搜索{{ $q }}-工作网</title>
    <meta name="keywords" content="{{ $q }}">
    <meta name="description" content="与“{{ $q }}”有关的结果">
@endsection

@section('content')
    <!--内容开始-->
    <section id="section">
        <!--内容开始-->
        <div class="position">此标签不可删</div>

        <div class="clearfix margincenter">
            <!--热门问题开始-->
            <div>
                <!-- 搜索框 -->
                <form action="/search" method="get" class="search-tag">
                <div class="searchdiv clearfix" id="searchdiv">
                    <p class="fl search-inner"><input class="fl" type="text" name="q" id="search" placeholder="搜索企业名称或岗位，提前熟悉面试真题" value="{{ $q }}"></p>
                    <input class="fr btn-sub" type="submit" value="">
                </div>
                </form>
                <!-- 没有找到相关结果 -->
                <!-- <h3 id="search-none"></h3> -->
                <!-- 请输入搜索关键词 -->
                <!-- <h3 id="search-inp"></h3> -->
                <div class="question-list">
                    @if (empty($questions))
                        @if ('' === $q)
                        <h3 id="search-inp"></h3>
                        @else
                        <h3 id="search-none"></h3>
                        @endif
                    @else
                    <div class="question-list-tab">
                        <a class="qa {{ 'newest' === $tab ? 'active' : '' }}" href='/search?q={{ $q }}&tab=newest'>最新问题</a>
                        <a class="qa {{ 'trending' === $tab ? 'active' : '' }}" href='/search?q={{ $q }}&tab=trending'>最多点赞</a>
                        <a class="qa {{ 'unanswered' === $tab ? 'active' : '' }}" href='/search?q={{ $q }}&tab=unanswered'>待回答问题</a>
                    </div>
                    <div id="paged">
                        <ul class="qa_ul" >

                        </ul>
                        {{ csrf_field() }}
                    </div>
                    @endif
                </div>
            </div>
            <!--热门问题结束-->
            <!--我要提问开始-->
            <div id="right-wrap">
                <div id="right-btn">
                <p>亲，你今天遇到什么问题了吗？</p>
                <a onclick="isLogin('/questions/ask',1)" href="javascript:void(0)">我要提问</a>
                </div>

            </div>
            <!--我要提问结束-->
        </div>
    </section>
    <!--内容结束-->
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
    <link rel="stylesheet" type="text/css" href="{{ asset('css/answers.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/selectize/selectize.default.css') }}"/>
@endsection

@section('javascripts')
    <script src="{{ asset('js/typeahead.bundle.js')}}"></script>
    <script src="{{ asset('js/handlebars.js')}}"></script>
    <script src="{{ asset('js/search.js') }}"></script>
    <script src="{{ asset('js/selectize.js') }}"></script>
    <script src="{{ asset('js/card.js') }}"></script>
    <script src="{{ asset('js/shared/util.js') }}"></script>
    <script src="{{ asset('js/view/paged_list.js') }}"></script>
    <script src="{{ asset('js/view/hover_menu.js') }}"></script>
    <script>
        $('#paged').pagedList({
            serverCall: 'search/list',
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
