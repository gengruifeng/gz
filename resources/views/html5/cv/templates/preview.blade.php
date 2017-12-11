
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>帮你解决简历的基本问题、难题！-工作网</title>
    <meta name="description" content="帮你解决简历的基本问题、难题！ 工作网欢迎您来分享您的职场经验。"/>
    <meta name="keywords" content="简历"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="{{ asset('css/css3/public.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/css3/resume.css') }}"/>
</head>
<body>
<!--头部开始-->
<div class="common-head clearfix">
    <a href="javascript:window.history.go(-1);" class=" btn-close-bac btn-bac"></a>
    <div class="logo">简历模板搜索</div>
</div>
<!--内容开始-->
<section>
    <div class="resume-wrap-download">
        <div class="resume-title">
            <h3>{{ $template->subject }}</h3>
            <p><span class="numbers">{{ $template->downloaded }}</span>人下载</p>
        </div>
        <div class="your-Tm-wrap">
            <div class="your-Tm">
                <img alt="" src="/templates/preview/{{ $template->preview }}">
            </div>
        </div>
        <a href="{{ url('cv/templates/email') }}" class="btn btn-long">发送此模板的网址至邮箱</a>
    </div>
</section>
<script src="{{ asset('js/jquery-2.1.0.js') }}"></script>
</body>
</html>
