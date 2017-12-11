@extends('layouts.layout')

@section('head')
    <title>私信-工作网</title>
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/inform.css') }}"/>
@endsection
@section('content')
    <form class="privateLetter" action="">
        <div>
            <a href="javascript:void(0)">私信</a>
            <a id="writeLetter" href="javascript:void(0)">写私信</a>
        </div>
        <div id="privatemsg">
            <ul></ul>
        </div>
    </form>
    {{ csrf_field() }}
    <!--私信遮罩层开始-->
    <div id="maskLayer" class="dispaly">
        <div id="writeMessage_1">
            <form action="">
                <div>
                    <h3>写私信</h3></div>
                <div>
                    <input type="search" id="privateMsgId" placeholder="搜索用户" />
                    <div id="writeKeyUpAt" class="display">

                    </div>
                </div>
                <div>
                    <textarea id="privateLetterMsg"></textarea>
                </div>
                <div>
                    <a href="javascript:void(0)" onclick="sendPrivateMsg()">发送</a>
                    <a id="call_oof" href="javascript:void(0)">取消</a>
                </div>
                {{ csrf_field() }}
            </form>
        </div>
    </div>
    <!--私信遮罩层结束-->
    <a href="javascript:;" id="btn-back-top"></a>
@endsection

@section('javascripts')
    <script src="{{ asset('js/public.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ asset('js/notice.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ asset('js/view/paged_list.js') }}"></script>
    <script src="{{ asset('js/shared/util.js') }}"></script>
    <script>
        $('#privatemsg').pagedList({
            serverCall: '/messages/page',
            kwargs: QueryString,
            hiddenClass: 'hidden'
        });
    </script>
@endsection