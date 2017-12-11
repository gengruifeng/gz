@extends('layouts.layout')

@section('head')
    <title>文章消息-工作网</title>
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/inform.css') }}"/>
@endsection
@section('content')
    <form class="aiticleMessage" action="">
        <div>
            <a href="javascript:void(0)">文章消息</a>
        </div>
        <div id="articlemsg">
            <ul></ul>
        </div>
    </form>
@endsection

@section('javascripts')
    <script src="{{ asset('js/personalCenter.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ asset('js/public.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ asset('js/view/paged_list.js') }}"></script>
    <script src="{{ asset('js/shared/util.js') }}"></script>
    <script>
        $('#articlemsg').pagedList({
            serverCall: '/notifications/articles/page',
            kwargs: QueryString,
            hiddenClass: 'hidden'
        });
    </script>
@endsection