@extends('layouts.layout')

@section('head')
    <title>{{ $template->subject }}下载-工作网</title>
    <meta name="keywords" content="{{ $template->subject }}, {{ $template->subject }}下载, 其他简历模板, 其他简历模板下载">
    <meta name="description" content="工作网提供海量优秀电子版简历模板、大学生简历模板、中英文简历模板下载。">
@endsection

@section('content')
    <!--内容开始-->
    <section class="resume resumeMine resum-choice resum-down clearfix" >
        <!-- 放模板简历 开始-->
        <h2 class="clearfix">{{ $template->subject }}<span class="fr"><b>{{ $template->downloaded }}</b>人下载</span></h2>
        <div class="fl main">
            <div class="your-Tm">
                <img alt="" width="680" src="/templates/preview/{{ $template->preview }}">
            </div>
        </div>
        <!--放模板简历 结束-->

        <!-- 简历提示 开始-->
        <div class="fr side">
        
            <div class="btns">
                <a href="javascript:void(0)" onclick="isLogin('/cv/templates/{{ $template->id }}/download', 1)" class="btn btn-yellow btn-wfull">下载此模板</a>
            </div>
        </div>
        <!--简历提示 结束-->
    </section>
    <!--内容结束-->
@endsection

@section('stylesheets')
<link href="{{ asset('css/resume.css') }}" rel="stylesheet" />
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
