@extends('layouts.layout')

@section('head')
    <title>简历管理-工作网</title>
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/resume.css') }}"/>
@endsection
@section('content')
    <!--内容开始-->
    <section class="resume resume-management">
        <!-- 头部 -->
        <div class="head">
            <a href="{{ url('resume/my') }}">+ 新建简历</a>
            <p>我的简历</p>
        </div>
        <div class="bd">
            <ul>
                <li class="resume-new">
                    <dl class="clearfix">
                        <dt class="fl">
                            <a href="{{ url('resume/my') }}">
                                定制简历
                            </a>
                        </dt>
                        <dd class="fl">
                            <p class="clearfix">
                                <span class="fl">1、不知道如何写简历？</span>
                                <span class="fl">2、需要找实习、找工作？</span>
                            </p>
                            <a href="{{ url('resume/my') }}">十分钟快速做出牛B的简历，快来试试吧~</a>
                        </dd>
                    </dl>
                </li>
            </ul>
        {{ csrf_field() }}

        </div>
        <!-- 修改 开始 -->
        <div class="resume-alert resume-alert-mod hide">
            <div class="inner">
                <h3>修改标题：应聘XX岗位-姓名-学校-手机号</h3>
                <div class="cnt">
                    <dl class="clearfix">
                        <dt class="fl">*简历标题：</dt>
                        <dd class="fl">
                            <input class="inptit" type="text" placeholder="请更新您的简历标题">
                            <p class="inptxt hide">请填写简历标题</p>
                        </dd>
                    </dl>
                </div>
                <div class="btns cleafix">
                    <div class="btn btn-yellow fr btnOk">完成</div>
                    <div class="btn fl btnCancel">取消</div>
                </div>
            </div>
        </div>
        <!-- 修改 结束 -->
        <!-- 删除 开始 -->
        <div class="resume-alert resume-alert-del hide">
            <div class="inner">
                <h3>删除简历：应聘XX岗位-姓名-学校-手机号</h3>
                <div class="cnt">
                    <p class="tips">简历删除后无法恢复！确定要删除这份简历吗？</p>
                </div>
                <div class="btns cleafix">
                    <div class="btn btn-yellow fr btnOk">完成</div>
                    <div class="btn fl btnCancel">取消</div>
                </div>
            </div>
        </div>
        <!-- 删除 结束 -->
    </section>
    <!--内容结束-->
@endsection

@section('javascripts')
    <script src="{{ asset('/js/resume.management.js') }}"></script>
    <script src="{{ asset('/js/jquery.imagezoom.min.js') }}"></script>
    <script src="{{ asset('js/view/paged_list.js') }}"></script>
    <script src="{{ asset('js/shared/util.js') }}"></script>
    <script>
        $('.bd').pagedList({
            serverCall: '/resume/pagelist',
            kwargs: QueryString,
            hiddenClass: 'hidden'
        });
    </script>

@endsection



