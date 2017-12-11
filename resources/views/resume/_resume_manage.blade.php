@if(!empty($resumelist))
    @foreach($resumelist as $resume)
        <li id="resumeList{{ $resume->id }}">
            <dl class="clearfix">
                <dt class="fl">
                    <a href="javascript:;" class="adefault">
                        <img src="{{asset('/images/resumemodel/'.$resume->model.'.png')}}" />
                    </a>
                </dt>
                <dd class="fl">
                    <h3><a data-text="{{ $resume->id }}" href="javascript:void(0);" class="adefault">{{ $resume->title }}</a></h3>
                    <div class="btn-modify clearfix">
                        <a href="javascript:void(0);" class="fl btn-del">删除</a>
                        <a href="javascript:void(0);" class="fl btn-modtitle">修改标题</a>
                        <a href="javascript:void(0)" class="fl btn-down" onclick="resumeDownload({{$resume->id}})" >下载简历</a>
                    </div>
                    <p>最后更新时间：{{ $resume->updated_at }}</p>
                </dd>
            </dl>
        </li>
    @endforeach
@endif
<script>
    $('.btnCancel').on('click',function(){
        $('.resume-alert-del').hide();
        $('.resume-alert-mod').hide();
    });
    // 删除
    $('.btn-del').on('click',function(){
         $('.resume-alert-del .btnOk').attr('data-text',0);
         var name = $(this).parent().prev().find('a').eq(0).text();
         var id = $(this).parent().prev().find('a').eq(0).attr('data-text');
         $('.resume-alert-del h3').text('删除简历：'+name);
         $('.resume-alert-del .btnOk').attr('data-text',id);
         $('.resume-alert-del').show();
    });
    // 修改
    $('.btn-modtitle').on('click',function(){
        $('.resume-alert-mod .btnOk').attr('data-text',0);
        var name = $(this).parent().prev().find('a').eq(0).text();
        var id = $(this).parent().prev().find('a').eq(0).attr('data-text');
        $('.resume-alert-mod h3').text('修改标题：'+name);
        $('.resume-alert-mod .inptit').val('');
        $('.resume-alert-mod .btnOk').attr('data-text',id);
        $('.resume-alert-mod').show();
    });


</script>
