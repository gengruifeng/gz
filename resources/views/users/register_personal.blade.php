<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>个人信息-工作网</title>
    <link rel="stylesheet" type="text/css" href="{{asset('/css/vlogin.css')}}"/>
</head>
<body class="bcimg3">
<!-- 主体开始  -->
<div class="vlogin loginMsg">
    <div class="inner">
        <a href="{{url('/')}}"><h1></h1></a>
        <h2>个人信息</h2>
        <form action="" id="personal-form" autocomplete="off">
            <div class="tp">
                <dl class="clearfix">
                    <dt class="fl"><b class="importantip">*</b>名号：</dt>
                    <dd @if(!empty($username)) class="fl correct" @else class="fl" @endif><input class="" type="text" id="display_name" name="display_name" value="{{$username}}" placeholder="您的名号"></dd>
                </dl>
                <dl class="clearfix radiowap">
                    <dt class="fl">状态：</dt>
                    <dd class="fl">
                        <input type="radio" id="school" name="dateformat" value="1" checked> <label for="school">在校学生</label>
                        <input type="radio" id="work" name="dateformat" value="2"> <label for="work">职场人士</label>
                    </dd>
                </dl>
                <dl class="clearfix radiowap">
                    <dt class="fl">性别：</dt>
                    <dd class="fl">
                        <input type="radio" id="boy" name="gender" value="1"> <label for="boy">男</label>
                        <input type="radio" id="girl" name="gender" value="2"> <label for="girl">女</label>
                        <input type="radio" id="scret" name="gender" value="3" checked> <label for="scret">保密</label>
                    </dd>
                </dl>
                <dl class="clearfix stepone">
                    <dt class="fl"><b class="importantip">*</b>学校：</dt>
                    <dd class="fl"><input type="text" placeholder="您的学校"  name="stepone" id="stepone"></dd>
                </dl>
                <dl class="clearfix steptwo">
                    <dt class="fl"><b class="importantip">*</b>专业：</dt>
                    <dd class="fl"><input type="text" placeholder="您的专业" name="steptwo" id="steptwo"></dd>
                </dl>
                <input type="hidden" id="userRegisterId" value="{{$userRegisterId}}">
                {{csrf_field()}}
                <input type="submit" value="下一步">
            </div>
        </form>
    </div>
</div>
<!-- 主体结束 -->
</body>
<script src="{{asset('/js/jquery-2.1.0.js')}}"></script>
<script src="{{asset('/js/jquery.validate.js')}}"></script>
<script src="{{asset('/js/vlogin.js')}}"></script>
</html>
