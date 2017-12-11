@extends('layouts.layout')

@section('head')
    <title>擅长领域-工作网</title>
@endsection

@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{ asset('css/personnel.css') }}"/>
@endsection
@section('content')
    <!--个人信息开始-->
    <div id="message">
        <div class="message">
            <div class="message_left">
                <a href="{{ url('account/settings') }}"><span></span><span>基本资料</span></a>
                <a href="{{ url('account/avatar') }}"><span></span><span>我的头像</span></a>
                <a href="{{ url('account/oauth') }}"><span></span><span>绑定设置</span></a>
                <a href="{{ url('account/safety') }}"><span></span><span>账户安全</span></a>
                <a class="onClick" href="{{ url('account/proficiency') }}"><span></span><span>擅长领域</span></a>
            </div>
            <div class="message_right">
                <!--擅长领域开始-->
                <div class="beGood">
                    <form id="nextfrom" method="post" action="{{ url('ajax/account/subcategory') }}" onsubmit="return false;">
                        <div><h3>擅长领域</h3></div>
                        @if(!empty($proficiencyInfo))
                        <div>
                            @foreach($proficiencyInfo as $info)
                                <a class="bg" onclick="selectcategory(this)"  href="javascript:void(0)">
                                    <div class="toggleimg">
                                        <img class="imgshow" src="{{ url("/categories/".$info->pic) }}" alt="">
                                        <img class="hide" src="{{ url("/categories/".$info->pic_hide) }}" alt="">
                                    </div>
                                    <!-- <div class="posbtm"> -->
                                    <input type="checkbox" value="{{ $info->id }}" name="categoryid[]" />
                                    <span></span>{{ $info->entity }}
                                    <!-- </div> -->
                                </a>
                            @endforeach
                            <button onclick="nextTags()">下一步</button>
                        </div>
                        @endif
                        {{ csrf_field() }}
                    </form>

                </div>
                <!--擅长领域结束-->
                <!--擅长领域>小选项开始-->
                <div class="beGoodCard display" id="beGoodCardss">
                    <form id ="subUserTagsfrom" action="{{ url('ajax/account/subusertags') }}" method="post" onsubmit="return false;">
                        <div>
                            <h3>擅长领域</h3></div>
                        <div>
                            <ul id="grful" class="clearfix">

                            </ul>
                            <button onclick="subUserTags()">保存</button>
                        </div>
                        {{ csrf_field() }}
                    </form>

                </div>
                <!--擅长领域>小选项结束-->
            </div>
        </div>
    </div>
    <!--个人信息结束-->
@endsection
@section('javascripts')
<script src="{{ asset('js/usertags.js') }}"></script>
@endsection