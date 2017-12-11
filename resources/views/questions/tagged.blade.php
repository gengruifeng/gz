@extends('layouts.layout')
@section('head')
    <title>标签-工作网</title>
@endsection
@section('content')
    <!--内容开始-->
    <section id="section">
        <!--内容开始-->
        <div class="position">此标签不可删</div>

        <div class="clearfix margincenter">
            <!--热门问题开始-->
            <div>
                <!-- 标签项目 -->
               <div class="labels" id="labels">
                   <h3>标签</h3>
                   <p class="clearfix">
                        @foreach ($tags as $tag)
                       <a href="javascript:;" class="fl">{{ $tag }}</a>
                        @endforeach
                   </p>
               </div>

                <div class="question-list">
                <div class="question-list-tab">
                    <a class="qa {{ 'newest' === $tab ? 'active' : '' }}" href='/questions/tagged/{{ $raw_tags }}?tab=newest' id="created_at">最新问题</a>
                    <a class="qa {{ 'trending' === $tab ? 'active' : '' }}" href='/questions/tagged/{{ $raw_tags }}?tab=trending' id="vote_up">最多点赞</a>
                    <a class="qa {{ 'unanswered' === $tab ? 'active' : '' }}" href='/questions/tagged/{{ $raw_tags }}?tab=unanswered' id="answered">待回答问题</a>
                </div>
                    @if (empty($questions))
                    <h3 id="search-none"></h3>
                    @else

                    <div id="paged">
                        <ul class="qa_ul" >

                        </ul>
                    </div>
                    @endif
                </div>
                {{ csrf_field() }}
                <input class='_token' id='page' type='hidden'>
            </div>
            <!--热门问题结束-->
            <!--我要提问开始-->
            <div id="right-wrap">
                <div id="right-btn">
                <p>亲，你今天遇到什么问题了吗？</p>
                <a onclick="isLogin('/questions/ask', 1)" href="javascript:void(0)">我要提问</a>
                </div>

            </div>
            <!--我要提问结束-->
        </div>

        <!--内容结束-->
    </section>


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
    <script src="{{ asset('js/asklist.js') }}"></script>
    <script src="{{ asset('js/card.js') }}"></script>
    <script src="{{ asset('js/selectize.js') }}"></script>
    <script src="{{ asset('js/shared/util.js') }}"></script>
    <script src="{{ asset('js/view/hover_menu.js') }}"></script>
    <script src="{{ asset('js/view/paged_list.js') }}"></script>
    <script>
        $('#paged').pagedList({
            serverCall: '/tagged/list/{{ $raw_tags }}',
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
@endsection
