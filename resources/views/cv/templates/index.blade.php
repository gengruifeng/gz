@extends('layouts.layout')

@section('head')
    <title>简历模板|中英文简历|简历模板下载-工作网</title>
    <meta name="keywords" content="简历模板,个人简历模板,简历模板下载,中文简历,电子版简历">
    <meta name="description" content="工作网提供海量 优秀电子版简历模板、大学生简历模板、中英文简历模板下载。">
@endsection

@section('content')
    <!--内容开始-->
    <section class="resume resumeTemplate">
        <!-- 搜索 开始-->
        <form action="/cv/templates/search" method="get" class="search-temp">
            <div class="search clearfix">
                <div class="fl search-inner "><input type="text" id="search-template" name="q" class="fl" placeholder="输入行业或职位名，找专属你的个人简历模板"></div>
                <!-- <a href="javascript:;" class="fr">找模板</a> -->
                <input type="submit" class="btn-sub fr" value="找模板">
            </div>
        </form>
        <!-- 选择 开始-->
        <div class="resumeTab">
            @if (! empty($professions))
            <dl class="clearfix">
                <dt class="fl">求职意向：</dt>
                <dd class="fl">
                    <a href="/cv/templates?profession={{ isset($query['language']) ? $query['language'] : '' }}{{ isset($query['colorscheme']) ? $query['colorscheme'] : '' }}" class="fl {{ empty($input['profession']) ? 'active' : '' }}">不限</a>
                    @foreach ($professions as $profession)
                    <a href="/cv/templates?profession={{ $profession->id }}{{ isset($query['language']) ? $query['language'] : '' }}{{ isset($query['colorscheme']) ? $query['colorscheme'] : '' }}" class="fl {{ $input['profession'] === (string) $profession->id ? 'active' : '' }}">{{ $profession->title }}</a>
                    @endforeach
                </dd>
            </dl>
            @endif
            <dl class="clearfix">
                <dt class="fl">简历语言：</dt>
                <dd class="fl">
                    <a href="/cv/templates?language={{ isset($query['profession']) ? $query['profession'] : '' }}{{ isset($query['colorscheme']) ? $query['colorscheme'] : '' }}" class="fl {{ empty($input['language']) ? 'active' : '' }}">不限</a>
                    <a href="/cv/templates?language=zh-cn{{ isset($query['profession']) ? $query['profession'] : '' }}{{ isset($query['colorscheme']) ? $query['colorscheme'] : '' }}" class="fl {{ $input['language'] === 'zh-cn' ? 'active' : '' }}">中文简历模板</a>
                    <a href="/cv/templates?language=en-us{{ isset($query['profession']) ? $query['profession'] : '' }}{{ isset($query['colorscheme']) ? $query['colorscheme'] : '' }}" class="fl {{ $input['language'] === 'en-us' ? 'active' : '' }}">英文简历模板</a>
                </dd>
            </dl>
            <dl class="clearfix last">
                <dt class="fl">模板风格：</dt>
                <dd class="fl">
                    <a href="/cv/templates?colorscheme={{ isset($query['profession']) ? $query['profession'] : '' }}{{ isset($query['language']) ? $query['language'] : '' }}" class="fl {{ $input['colorscheme'] !== '0' && $input['colorscheme'] !== '1'? 'active' : '' }}">不限</a>
                    <a href="/cv/templates?colorscheme=0{{ isset($query['profession']) ? $query['profession'] : '' }}{{ isset($query['language']) ? $query['language'] : '' }}" class="fl {{ $input['colorscheme'] === '0' ? 'active' : '' }}">黑白简历模版</a>
                    <a href="/cv/templates?colorscheme=1{{ isset($query['profession']) ? $query['profession'] : '' }}{{ isset($query['language']) ? $query['language'] : '' }}" class="fl {{ $input['colorscheme'] === '1' ? 'active' : '' }}">彩色简历模版</a>
                </dd>
            </dl>
        </div>
        <!--选择 结束-->

        <!-- 列表 开始-->
        <div class="resumeList">
            <div class="tit clearfix">
                <a href="/cv/templates?tab=latest{{ isset($query['profession']) ? $query['profession'] : '' }}{{ isset($query['language']) ? $query['language'] : '' }}{{ isset($query['colorscheme']) ? $query['colorscheme'] : '' }}" class="fl {{ $input['tab'] !== 'trending' ? 'active' : '' }}">最&nbsp;新</a>
                <a href="/cv/templates?tab=trending{{ isset($query['profession']) ? $query['profession'] : '' }}{{ isset($query['language']) ? $query['language'] : '' }}{{ isset($query['colorscheme']) ? $query['colorscheme'] : '' }}" class="fl {{ $input['tab'] === 'trending' ? 'active' : '' }}">人&nbsp;气</a>
            </div>
            <div id="paged">
                <ul class="cnt">

                </ul>
            </div>
        </div>
    </section>
    <a href="javascript:;" id="btn-back-top"></a>
    <!--内容结束-->
@endsection

@section('stylesheets')
<link href="{{ asset('css/resume.css') }}" rel="stylesheet" />
@endsection

@section('javascripts')
    <script src="{{ asset('js/typeahead.bundle.js')}}"></script>
    <script src="{{ asset('js/handlebars.js')}}"></script>
    <script src="{{ asset('js/shared/util.js') }}"></script>
    <script src="{{ asset('js/view/paged_list.js') }}"></script>
    <script>
        $('#paged').pagedList({
            serverCall: '/cv/templates/list',
            kwargs: QueryString,
            hiddenClass: 'hidden'
        });
    </script>
    <script>
        $(function () {
            var bestPictures = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('subject'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: '/cv/templates/search?q=%QUERY',
                    wildcard: '%QUERY'
                },
            });

            $('#search-template').typeahead({
                hint: false//阻止默认第一个到输入框

            },{
                name: 'best-pictures',
                display: 'subject',
                source: bestPictures
            });
            $('#search-template').on('typeahead:selected', function (e, datum) {
                location.href="/cv/templates/"+datum.id;
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
