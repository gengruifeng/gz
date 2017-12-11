@extends('layouts.layout')

@section('head')
    <title>系统通知-工作网</title>
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/inform.css') }}"/>
@endsection
@section('content')
    <form class="systemInform" action="">
        <div>
            <a href="javascript:void(0)">系统通知</a>
        </div>
        <div id="systemmsg">
            <ul></ul>
        </div>
    </form>
    <a href="javascript:;" id="btn-back-top"></a>
@endsection

@section('javascripts')
    <script src="{{ asset('js/personalCenter.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ asset('js/public.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ asset('js/view/paged_list.js') }}"></script>
    <script src="{{ asset('js/shared/util.js') }}"></script>
    <script>
        $('#systemmsg').pagedList({
            serverCall: '/notifications/others/page',
            kwargs: QueryString,
            hiddenClass: 'hidden'
        });
    </script>
@endsection