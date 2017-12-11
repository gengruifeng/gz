@extends('html5.layouts.layoutm')

@section('head')
    <title>{{ $article->subject }}-工作网</title>
    <meta name="description" content="{{ $article->subject }} 工作网欢迎您来分享您的职场经验。"/>
    <meta name="keywords" content="{{ $tagsToString }}"/>
    <meta name="viewport" content="width=device-width,initial-scale=1, minimum-scale=1.0, maximum-scale=1, user-scalable=no">
@endsection

@section('content')
    <form class="article" id="comment_form" action="/ajax/articles/{{ $article->id }}/comments" method="post">
        <div>
            <div>
                @foreach ($tags as $tag)
                    <a href="javascript:void(0)">{{ $tag->name }}<span></span></a>
                @endforeach
            </div>
            <div>
                <h3>{{ $article->subject }}</h3>
            </div>
            <div>
                <p>
                    <a href="javascript:;" data-text="{{$article->uid}}">{{ $author }}</a> ·
                    <a href="javascript:void(0)">{{ $updated_at }}</a> ·
                    <a href="javascript:void(0)">{{ $article->viewed }}阅读</a>
                </p>
            </div>
            <div>
                <p>{!! $article->detail !!}</p>
            </div>

        </div>
    </form>
@endsection

@section('stylesheets')
<link href="{{ asset('css/css3/inform-response.css') }}" rel="stylesheet" />
@endsection

@section('javascripts')
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
