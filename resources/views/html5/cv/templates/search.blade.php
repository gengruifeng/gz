<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
    <meta name="description" content="帮你解决简历的基本问题、难题！ 工作网欢迎您来分享您的职场经验。"/>
    <meta name="keywords" content="简历"/>
    <meta name="viewport" content="width=device-width,initial-scale=1, minimum-scale=1.0, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="{{ asset('css/css3/public.css')}}"/>
    <link rel="stylesheet" href="{{ asset('css/css3/resume.css') }}"/>
</head>
<body>
<!--头部开始-->
<div class="common-head clearfix">
        <div class="logo"><img class="logoimg" src="{{ url('images/logos/logosecd.png')}}" alt=""></div>
    </div>
<!--内容开始-->
<section>
    <div class="resume-wrap">
        <div class="resume-search resume-search-result clearfix">
            <a href="{{ url('cv/templates') }}" class="btn-bac fl"></a>
            <form action="/cv/templates/search" method="get">
                <input type="search" class="fl inp" name="q" autocomplete="off" value="{{ $input['q'] }}" placeholder="输入行业或职位名，找专属你的个人简历模板">
                <input type="submit" class="inpbtn fl" value="找模板">
            </form>
        </div>
        <div id="searchlist">
            <ul></ul>
        </div>
    </div>
    <a href="javascript:;" id="btn-back-top"></a>
</section>
<script src="{{ asset('js/jquery-2.1.0.js') }}"></script>
<script src="{{ asset('jswrap/public.js') }}"></script>

<script src="{{ asset('js/shared/util.js') }}"></script>
<script src="{{ asset('js/view/paged_list.js') }}"></script>
<script>
    $('#searchlist').pagedList({
        serverCall: '/cv/templates/search/list',
        kwargs: QueryString,
        hiddenClass: 'hidden'
    });
</script>

</body>
</html>
