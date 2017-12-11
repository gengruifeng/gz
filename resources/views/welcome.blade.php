@extends('layouts.layout')

@section('head')
    <title>工作网-最专业的大学生职业成长平台_简历模板_名企面试题库_求职问答</title>
    <meta name="keywords" content="个人简历,简历模板,求职简历,简历模板下载,找工作,互联网招聘,笔试面试,笔试题库,IT面试">
    <meta name="description" content="工作网是最权威的大学生职业成长平台，提供最新最全的求职简历模板、全国真实的互联网招聘信息、20万名企面试题库，帮助大学生解决面试过程中遇到的所有问题。">
@endsection

@section('content')
    <!--内容开始-->
    <section class="resume homepage">
        <!-- banner 要做成轮播 开始-->
        <div class="banner">
            <div class="bigimg">
                <img src="../images/homepage/hb.jpg" alt="">
                <a href="/resume/my">立即制作</a>
            </div>
        </div>
        <!--banner 要做成轮播 结束-->
        <!-- search 开始-->
        <div class="search search-home">
            <div class="inner">
                <h2>20万名企面试题库，高薪职位等你拿</h2>
                <form action="/search">
                    <div class="searchdiv clearfix">
                        <div class="fl search-inner"><input type="text" id="search" name="q" placeholder="搜索企业名称或岗位，提前熟悉面试真题"></div>
                        <input class="fr btn-sub" type="submit" value="">
                    </div>
                </form>
            </div>
        </div>
        <!--search 结束-->
        <!-- list-question 开始-->
        <div class="question">
            <ul class="clearfix">
                <li class="fl first">
                    <a href="/questions/tagged/百度;面试题"><img class="dowebok" src="../images/homepage/icon01.png" alt=""></a>
                    <p>百度面试题</p>
                </li>
                <li class="fl">
                    <a href="/questions/tagged/腾讯;面试题"><img class="dowebok" src="../images/homepage/icon02.png" alt=""></a>
                    <p>腾讯面试题</p>
                </li>
                <li class="fl">
                    <a href="/questions/tagged/阿里巴巴;面试题"><img class="dowebok" src="../images/homepage/icon03.png" alt=""></a>
                    <p>阿里巴巴面试题</p>
                </li>
                <li class="fl">
                    <a href="/questions/tagged/京东;面试题"><img class="dowebok" src="../images/homepage/icon04.png" alt=""></a>
                    <p>京东面试题</p>
                </li>
                <li class="fl">
                    <a href="/questions/tagged/google;面试题"><img class="dowebok" src="../images/homepage/icon05.png" alt=""></a>
                    <p>Google面试题</p>
                </li>
                <li class="fl">
                    <a href="/questions/tagged/facebook;面试题"><img class="dowebok" src="../images/homepage/icon06.png" alt=""></a>
                    <p>Facebook面试题</p>
                </li>
                <li class="fl">
                    <a href="/questions/tagged/小米;面试题"><img class="dowebok" src="../images/homepage/icon07.png" alt=""></a>
                    <p>小米面试题</p>
                </li>
                <li class="fl last">
                    <a href="/questions/tagged/奇虎360;面试题"><img class="dowebok" src="../images/homepage/icon08.png" alt=""></a>
                    <p>奇虎360面试题</p>
                </li>
            </ul>
            <div class="question-list">
                <h3>快速获得职业方向面试真题</h3>
                <p class="clearfix">
                    <a href="/questions/tagged/保险" class="fl">保险</a>
                    <a href="/questions/tagged/财务" class="fl">财务</a>
                    <a href="/questions/tagged/电商" class="fl">电商</a>
                    <a href="/questions/tagged/记者" class="fl">记者</a>
                    <a href="/questions/tagged/客服" class="fl">客服</a>
                    <a href="/questions/tagged/律师" class="fl">律师</a>
                    <a href="/questions/tagged/人力" class="fl">人力</a>
                    <a href="/questions/tagged/软件" class="fl">软件</a>
                    <a href="/questions/tagged/市场" class="fl last">市场</a>
                    <a href="/questions/tagged/通信" class="fl">通信</a>
                    <a href="/questions/tagged/通用" class="fl">通用</a>
                    <a href="/questions/tagged/投行" class="fl">投行</a>
                    <a href="/questions/tagged/外贸" class="fl">外贸</a>
                    <a href="/questions/tagged/销售" class="fl">销售</a>
                    <a href="/questions/tagged/行政" class="fl">行政</a>
                    <a href="/questions/tagged/银行" class="fl">银行</a>
                    <a href="/questions/tagged/证券" class="fl">证券</a>
                    <a href="/questions/tagged/咨询" class="fl last">咨询</a>
                </p>
            </div>
        </div>
        <!--list-question 结束-->
    </section>
    <a href="javascript:;" id="btn-back-top"></a>
    <!--内容结束-->
@endsection

@section('stylesheets')
<link href="{{ asset('css/homepage.css') }}" rel="stylesheet" />
<link href="{{ asset('css/vcommon.css') }}" rel="stylesheet" />
@endsection

@section('javascripts')
    <script src="{{ asset('js/typeahead.bundle.js')}}"></script>
    <script src="{{ asset('js/handlebars.js')}}"></script>
<script src="{{ asset('js/search.js') }}"></script>
<script src="{{ asset('js/homepage.js') }}"></script>
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

