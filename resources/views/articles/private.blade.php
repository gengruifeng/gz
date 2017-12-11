@extends('layouts.layout')

@section('head')
    <title>我的文章-工作网</title>
@endsection

@section('content')
    <form class="myArticle">
        <div>
            <a href="javascript:void(0)">我的文章</a>
        </div>
        <div id="myarticle">
            <ul></ul>
        </div>
    </form>
@endsection

@section('stylesheets')
    <link href="{{ asset('css/common.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/inform.css') }}" rel="stylesheet" />
@endsection

@section('javascripts')
    <script src="{{ asset('js/public.js') }}"></script>
    <script src="{{ asset('js/view/paged_list.js') }}"></script>
    <script src="{{ asset('js/shared/util.js') }}"></script>
    <script>
        $('#myarticle').pagedList({
            serverCall: '/profile/articlepage',
            kwargs: QueryString,
            hiddenClass: 'hidden'
        });
    </script>
@endsection
