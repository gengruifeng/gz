<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>擅长领域-工作网</title>
    <link rel="stylesheet" type="text/css" href="{{asset('/css/vlogin.css')}}"/>
    <link rel="stylesheet" href="{{ asset('css/vcommon.css') }}"/>
</head>
<body class="bcimg5">
<!-- 主体开始  -->
<div class="vlogin loginExpert">
    <div class="inner">
        <a href="{{url('/')}}"><h1></h1></a>
        <h2>擅长领域</h2>
        <form action="" method="post" id="begoodat-form" onsubmit="return false" autocomplete="off">
            @if(!empty($goodat['categorys']))
                <ul class="tabs clearfix">
                    @foreach($goodat['categorys'] as $category)
                    <li class="fl"><a href="javascript:;" class=" ">{{$category['cateName']}}</a></li>
                    @endforeach
                </ul>
            @endif
            <!-- panes -->
                <div class="panes" id="grful">
                    @if(!empty($goodat['tags']))
                        @foreach($goodat['tags'] as $tags)
                            <div class="clearfix pane">
                                @foreach($tags as $tag)
                                <a href="javascript:;" class="fl active" onclick="selecttag(this)"><input type="checkbox" value="{{$tag->id}}"><span>{{$tag->name}}</span></a>
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                </div>
                {{csrf_field()}}
                <input onclick="begoodat({{$userRegisterId}})" type="submit" value="完成注册">
        </form>
    </div>
</div>
<div class="dialogcom dialogcom_warn hide ">
    <form action="">
        <span>操作过于频繁，请明天再来</span>
        <input class="_token" type="hidden" name="" id="" value="">
    </form>
</div>
<!-- 主体结束 -->
</body>
<script src="{{asset('/js/jquery-2.1.0.js')}}"></script>
<script src="{{asset('/js/register.js')}}"></script>
<script src="{{asset('/js/common.js')}}"></script>
<script src="{{asset('/js/tabs.js')}}"></script>
<script>
    $( function() {
        $(".tabs").tabs(".pane", {
            onClick: function () {
                // console.log(this.getConf(), this.getCurrentPane(), this.getCurrentTab(), this.getIndex(), this.getPanes(), this.getTabs());
            }
        });

    } );
</script>
</html>


