@extends('layouts.layout')

@section('head')
    <title>{{ $article->subject }}-工作网</title>
    <meta name="description" content="{{ $article->subject }} 工作网欢迎您来分享您的职场经验。"/>
    <meta name="keywords" content="{{ $tagsToString }}"/>

@endsection

@section('content')
    <form class="article" id="comment_form" action="/ajax/articles/{{ $article->id }}/comments" method="post">
        <div>
            <div class="article-tags">
                @foreach ($tags as $tag)
                    <span>{{ $tag->name }}</span>
                @endforeach
            </div>
            <div>
                <h3>{{ $article->subject }}</h3>
            </div>
            <div class="article-info">
                <p class="clearfix">
                    <a href="/profile/{{ $article->uid }}"  class="rich-avatar"  data-card-url="/users/card/{{$article->uid}}" alt="{{ $userarr['display_name'] }}" data-text="{{$article->uid}}">{{ $author }}</a> ·
                    <span>{{ $updated_at }}</span> ·
                    <span>{{ $article->viewed }}阅读</span>
                    @if ($article->uid === $userarr['uid'] && 0 === $article->standard)
                        <a href="/articles/{{ $article->id }}/revise">编辑</a>
                    @endif
                </p>
            </div>
            <!--文章内容-->
            <div class="article-detaill">
                <p>{!! $article->detail !!}</p>
            </div>
            <div>
                @if(!empty($is_praise) && $is_praise > 0)
                <a data-url="/ajax/articles/{{ $article->id }}/voteup" href="javascript:void(0)" id="voteupurl" class="onClick" >赞 <span id="voteup">{{ $article->vote_up }}</span></a>
                @else
                    <a data-url="/ajax/articles/{{ $article->id }}/voteup" href="javascript:void(0)" id="voteupurl" >赞 <span id="voteup">{{ $article->vote_up }}</span></a>
                @endif

                    @if(!empty($is_collect) && $is_collect > 0)
                        <a data-url="/ajax/articles/{{ $article->id }}/star" href="javascript:void(0)" id="staredurl" class="onClick"  >收藏 <span id="stared">{{ $article->stared }}</span></a>
                    @else
                        <a data-url="/ajax/articles/{{ $article->id }}/star" href="javascript:void(0)" id="staredurl" >收藏 <span id="stared">{{ $article->stared }}</span></a>
                    @endif
            </div>
        </div>
        <!-- 评论开始 -->
        <div class="article-ping-list">
            <div class="article-ping-title">评论 <span id="comment-count">{{ $comment_count }}</span></div>

            <div class="article-ping-add">
                <div style="display:none;"></div>
                @if (0 < $userarr['uid'])
                    <div>
                        {{--<ul>--}}
                        {{--<li class="">--}}
                        <div class="clearfix tp">
                            <div class="article-ping-add-head fl">
                                <img  src="/avatars/60/{{ $userarr['avatar'] }}"/>
                            </div>
                            <div class="fr article-ping-add-text">
                                <textarea id="content" placeholder="请输入..." name="content"></textarea>
                            </div>
                        </div>

                        <div class="btns clearfix">
                            <a class="fr" href="javascript:void(0)" id="comment">提交回答</a>
                        </div>
                        {{--</li>--}}
                        {{--</ul>--}}
                    </div>
                @endif
            </div>
            <!-- 评论列表 -->
            <div class="article-ping-content" id="paged">
                <ul>
                </ul>
            </div>
        </div>
        {{ csrf_field() }}
        <input name="article_id" type="hidden" value="{{ $article->id }}" />
    </form>
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
<link href="{{ asset('css/simditor.css') }}" rel="stylesheet" />
<link href="{{ asset('css/simditor-mention.css') }}" rel="stylesheet" />
<link href="{{ asset('css/inform.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/selectize/selectize.default.css') }}"/>
@endsection

@section('javascripts')
<script src="{{ asset('js/module.js') }}"></script>
<script src="{{ asset('js/hotkeys.js') }}"></script>
<script src="{{ asset('js/uploader.js') }}"></script>
<script src="{{ asset('js/simditor.js') }}"></script>
<script src="{{ asset('js/simditor-mention.js') }}"></script>
<script src="{{ asset('js/article.js') }}"></script>
<script src="{{ asset('js/selectize.js') }}"></script>
<script src="{{ asset('js/shared/util.js') }}"></script>
<script src="{{ asset('js/view/hover_menu.js') }}"></script>
<script src="{{ asset('js/card.js') }}"></script>
<script src="{{ asset('js/view/paged_list.js') }}"></script>
<script>
    $('#paged').pagedList({
        serverCall: '/ajax/articles/{{ $article->id }}/comments',
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
