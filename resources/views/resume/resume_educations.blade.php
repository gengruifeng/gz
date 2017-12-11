<!DOCTYPE html>
<html class="autoheight">
<head>
    <meta charset="UTF-8">
    <title>教育背景-工作网</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/common.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vlogin.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/resume-step.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/datepicker.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/avatar.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}"/>
</head>
<body>

<!--内容开始-->
<section class="resume-step resume-two clearfix">
    <div class="inner">
        <div class="secwrap">
            <!-- 容器 -->
            <textarea style="display:none" id="template">
                <!-- 容器 -->
            <div class="sec{0}">
                <dl class="clearfix school">
                    <dt class="fl">院校名称：</dt>
                    <dd class="fl">
                        <input type="text" readonly onclick="showschool(this,'{0}',event)" placeholder="" class="school_name inpbtn" name="school_name-{0}" id="school_name-{0}">
                    </dd>
                </dl>

                <!-- 院校名称 选择 开始 -->
                <div class="slidmsg academy hide">
                    <div class="academy-city clearfix ">
                        @foreach($school as $k=>$v)
                        <a href="javascript:;" onclick="city(this,'{{ $k }}','{0}',event)" class="fl">{{ $v['name'] }}</a>
                        @endforeach
                    </div>
                    @foreach($school as $k=>$v)
                    <div class="academy-name city{{ $k }} hide">
                        <h2>{{ $v['name'] }}</h2>
                        <input class="data-city"  type="text" data-city="{{ $k }}"  onclick="sousuoschool(event)" name="seachschool" placeholder="搜索你的院校名称" >
                        <div class="btm clearfix">
                            @foreach($v['sub'] as $kk=>$vv)
                            <a href="javascript:;" class="fl">{{ $vv }}</a>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                </div>

                <dl class="clearfix sexspan">
                    <dt class="fl">所学专业：</dt>
                    <dd class="fl">
                        <input type="text" class="expert_name" name="expert_name-{0}" id="expert_name-{0}">
                    </dd>
                </dl>
                <div class="timecheck clearfix">
                    <dl class="fl duringSchool birthday">
                        <dt class="fl">入学时间：</dt>
                        <dd class="fl">
                            <input type="text"  class="input-startday time_start" name="time_start-{0}">
                        </dd>
                    </dl>
                    <dl class="fl duringSchool birthday">
                        <dt class="fl">&nbsp;&nbsp;毕业时间：</dt>
                        <dd class="fr">
                            <input type="text" id="time_end-{0}" class="input-startday time_end" name="time_end-{0}">
                        </dd>
                    </dl>
                </div>
                <dl class="clearfix clearfix">
                    <dt class="fl">学历：</dt>
                    <dd class="fl">
                        <select name="level-{0}" id="level-{0}" class="level">
                            <option value="">请选择</option>
                            <option value="高中/中专/中技">高中/中专/中技</option>
                            <option value="大专">大专</option>
                            <option value="本科/学士">本科/学士</option>
                            <option value="硕士/研究生">硕士/研究生</option>
                            <option value="MBA">MBA</option>
                            <option value="博士及以上">博士及以上</option>
                            <option value="其他">其他</option>

                        </select>
                    </dd>
                </dl>
                <div class="addmore clearfix">
                    <a href="javascript:;" class="fr btn-close" onclick="removeinfo('{0}')" ></a>
                </div>
            </div>
            </textarea>
        </div>
        <div class="head" id="destination">
            <img id="userimg" onclick="dialogupload()" src="{{ !empty($persons->resumeavatar)? url('/resume/'.$persons->resumeavatar): '/avatars/120/head.png' }}"/>
        </div>
        <h3><span>教育背景</span></h3>
            <form action="" id="resumeTwoForm" autocomplete="off" >
                {{ csrf_field() }}
                <div class="stepmsg">
                    @if(!empty($educations))
                    @foreach($educations as $educationkey => $education)
                        <div class="sec{{$educationkey}}">
                            <dl class="clearfix school">
                                <dt class="fl">院校名称：</dt>
                                <dd class="fl">
                                    <input type="text" readonly onclick="showschool(this,event)" placeholder="" value="{{$education['school']}}" class="school_name inpbtn" name="school_name-{{$educationkey}}" id="school_name-{{$educationkey}}">
                                </dd>
                            </dl>

                            <!-- 院校名称 选择 开始 -->
                            <div class="slidmsg academy hide">
                                <div class="academy-city clearfix ">
                                    @foreach($school as $k=>$v)
                                        <a href="javascript:;" onclick="city(this,'{{ $k }}','{{$educationkey}}',event)" class="fl">{{ $v['name'] }}</a>
                                    @endforeach
                                </div>
                                @foreach($school as $k=>$v)
                                    <div class="academy-name city{{ $k }} hide">
                                        <h2>{{ $v['name'] }}</h2>
                                        <input class="data-city" onclick="sousuoschool(event)"  type="text" data-city="{{ $k }}"  name="seachschool" placeholder="搜索你的院校名称" >
                                        <div class="btm clearfix">
                                            @foreach($v['sub'] as $kk=>$vv)
                                                <a href="javascript:;" class="fl">{{ $vv }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                            <dl class="clearfix sexspan">
                                <dt class="fl">所学专业：</dt>
                                <dd class="fl">
                                    <input type="text" class="expert_name" name="expert_name-{{$educationkey}}" value="{{$education['department']}}" id="expert_name-{{$educationkey}}">
                                </dd>
                            </dl>
                            <div class="timecheck clearfix">
                                <dl class="fl duringSchool birthday">
                                    <dt class="fl">入学时间：</dt>
                                    <dd class="fl">
                                        <input type="text"  class="input-startday time_start" value="{{$education['enrolled']}}" name="time_start-{{$educationkey}}">
                                    </dd>
                                </dl>
                                <dl class="fl duringSchool birthday">
                                    <dt class="fl">&nbsp;&nbsp;毕业时间：</dt>
                                    <dd class="fr">
                                        <input type="text" id="time_end-{{$educationkey}}" value="{{$education['graduated']}}" class="input-startday time_end" name="time_end-{{$educationkey}}">
                                    </dd>
                                </dl>
                            </div>
                            <dl class="clearfix clearfix">
                                <dt class="fl">学历：</dt>
                                <dd class="fl">
                                    <select name="level-{{$educationkey}}" value="{{$education['education']}}" id="level-{{$educationkey}}" class="level">
                                        <option value="{{$education['education']}}">{{$education['education']}}</option>
                                        <option value="高中/中专/中技">高中/中专/中技</option>
                                        <option value="大专">大专</option>
                                        <option value="本科/学士">本科/学士</option>
                                        <option value="硕士/研究生">硕士/研究生</option>
                                        <option value="MBA">MBA</option>
                                        <option value="博士及以上">博士及以上</option>
                                        <option value="其他">其他</option>

                                    </select>
                                </dd>
                            </dl>
                            @if($educationkey!=0)
                            <div class="addmore clearfix">
                                <a href="javascript:;" class="fr btn-close" onclick="removeinfo({{$educationkey}})" ></a>
                            </div>
                            @endif
                        </div>
                    @endforeach
                    @else
                        <div class="sec0">
                            <dl class="clearfix school">
                                <dt class="fl">院校名称：</dt>
                                <dd class="fl">
                                    <input type="text" readonly onclick="showschool(this,'0',event)" placeholder="" class="school_name inpbtn" name="school_name-0" id="school_name-0">
                                </dd>
                            </dl>

                            <!-- 院校名称 选择 开始 -->
                            <div class="slidmsg academy hide">
                                <div class="academy-city clearfix ">
                                    @foreach($school as $k=>$v)
                                        <a href="javascript:;" onclick="city(this,'{{ $k }}',0,event)" class="fl">{{ $v['name'] }}</a>
                                    @endforeach
                                </div>
                                @foreach($school as $k=>$v)
                                    <div class="academy-name city{{ $k }} hide">
                                        <h2>{{ $v['name'] }}</h2>
                                        <input class="data-city"  onclick="sousuoschool(event)" type="text" data-city="{{ $k }}"  name="seachschool" placeholder="搜索你的院校名称" >
                                        <div class="btm clearfix">
                                            @foreach($v['sub'] as $kk=>$vv)
                                                <a href="javascript:;" class="fl">{{ $vv }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                            <dl class="clearfix sexspan">
                                <dt class="fl">所学专业：</dt>
                                <dd class="fl">
                                    <input type="text" class="expert_name" name="expert_name-0" id="expert_name-0">
                                </dd>
                            </dl>
                            <div class="timecheck clearfix">
                                <dl class="fl duringSchool birthday">
                                    <dt class="fl">入学时间：</dt>
                                    <dd class="fl">
                                        <input type="text"  class="input-startday time_start" name="time_start-0">
                                    </dd>
                                </dl>
                                <dl class="fl duringSchool birthday">
                                    <dt class="fl">&nbsp;&nbsp;毕业时间：</dt>
                                    <dd class="fr">
                                        <input type="text" id="time_end-0" class="input-startday time_end" name="time_end-0">
                                    </dd>
                                </dl>
                            </div>
                            <dl class="clearfix clearfix">
                                <dt class="fl">学历：</dt>
                                <dd class="fl">
                                    <select name="level-0" id="level-0" class="level">
                                        <option value="">请选择</option>
                                        <option value="高中/中专/中技">高中/中专/中技</option>
                                        <option value="大专">大专</option>
                                        <option value="本科/学士">本科/学士</option>
                                        <option value="硕士/研究生">硕士/研究生</option>
                                        <option value="MBA">MBA</option>
                                        <option value="博士及以上">博士及以上</option>
                                        <option value="其他">其他</option>

                                    </select>
                                </dd>
                            </dl>
                        </div>
                    @endif
                </div>
                <!-- 添加更多 -->
                <input type="hidden" value="{{empty($i)?1:$i}}" id="i">
                <div class="addmore clearfix">
                    <a href="javascript:;" class="fl btn-more" id="add">添加更多教育背景</a>
                </div>

                <!-- 按钮组 -->
                <div class="btns clearfix">
                    <input class="btn btn-yellow btn-yellow-inner fr" type="submit" value="下一步：实习经历">
                    <a class="fl btn btn-grey btn-grey-inner" href="javascript:;" onclick="twoback('/resume/my')">上一步</a>
                </div>
            </form>
    </div>

</section>
<!--内容结束-->
<!-- 提示信息 开始-->
<div class="dialogcom dialogcom_yes hide">
    <form action="">
        <span>提问成功</span>
        <input class="_token" type="hidden" name="" id="" value="">
    </form>
</div>
<div class="dialogcom dialogcom_warn hide ">
    <form action="">
        <span>操作过于频繁，请明天再来</span>
        <input class="_token" type="hidden" name="" id="" value="">
    </form>
</div>
<div class="dialogcom dialogcom_wrong hide">
    <form action="">
        <span>操作过于频繁，请明天再来</span>
        <input class="_token" type="hidden" name="" id="" value="">
    </form>
</div>
<!-- 提示信息 结束-->
<!--上传图 开始-->
<div class="imgup hide">
    <div class=" av-step av-resume">
        <div class="hd-title clearfix">照片设置
            <a href="javascript:;" class="btn-close fr"></a>
        </div>
        <div class="clearfix av-resumebtm">
            <div class="fl av-main">
                <div class="imageBox">
                    <div class="thumbBox"></div>
                </div>
                <div class="action">
                    <div class="btn-numb clearfix">
                        <input type="button" id="btnZoomIn" class="Btnsty_peyton" value=""  >
                        <input type="button" id="btnZoomOut" class="Btnsty_peyton" value="" >
                    </div>
                    <div class="btn-mod clearfix">
                        <div class="new-contentarea tc fl">
                            <a href="javascript:void(0)" class="upload-img">
                                <label for="upload-file">上传真实头像</label>
                            </a>
                            <input type="file" class="" name="upload-file" id="upload-file" />
                        </div>
                        <input type="button" id="btnCrop"  class="Btnsty_peyton fl" value="保存头像">
                    </div>
                    <p class="tips">只支持jpg、png、jpeg、bmp，图片大小5M以内</p>
                </div>
            </div>
            <div class="fl av-side">
                <div class="title">头像预览</div>
                <div id="">
                    <img id="72l22" src="../images/riji3.png"/>
                </div>
            </div>
        </div>

    </div>
</div>
<!--上传图 结束-->
<script src="{{ asset('js/jquery-2.1.0.js')}}"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/jquery.form.js')}}"></script>
<script src="{{ asset('js/jquery.validate.js')}}"></script>
<script src="{{ asset('js/plupload.full.min.js') }}"></script>
<script src="{{ asset('js/upload.js') }}"></script>
<script src="{{ asset('js/vlogin.js') }}"></script>
<script src="{{ asset('admin/assets/js/ace/ace.js')}}"></script>
<script src="{{ asset('admin/assets/js/date-time/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/date-time/locales/bootstrap-datepicker.zh-CN.js') }}"></script>
<script src="{{ asset('js/resumeFirst.js')}}"></script>
<script src="{{ asset('js/resumetwo.js')}}"></script>
<script src="{{ asset('js/tabs.js')}}"></script>
<script src="{{ asset('js/common.js')}}"></script>
<script src="{{ asset('js/cropbox.js') }}"></script>

<script>
    var _token = "{{ csrf_token() }}";
    window.onload = function(){
        $(function () {

            var options =
            {
                thumbBox: '.thumbBox',
                spinner: '.spinner',
                imgSrc: '../images/resume-default.png'
            };
            var cropper = $('.imageBox').cropbox(options);
            $('#upload-file').on('change', function(){

                var aa = document.getElementById("upload-file").value.toLowerCase().split('.'); //以“.”分隔上传文件字符串

                if (aa[aa.length - 1] == 'jpg' || aa[aa.length - 1] == 'bmp'

                        || aa[aa.length - 1] == 'png' || aa[aa.length - 1] == 'jpeg') //判断图片格式
                {
                    var imagSize = document.getElementById("upload-file").files[0].size;
                    if (imagSize > 1024 * 1024 * 5)
                    {
                        dialogcom_wrong("图片大小在5M以内，为：" + imagSize / (1024 * 1024) + "M");
                        $(this).val('');
                        return false;
                    }
                } else {
                    dialogcom_wrong('请选择格式为*.jpg、*.bmp、*.png、*.jpeg 的图片'); //jpg和jpeg格式是一样的只是系统Windows认jpg，Mac OS认jpeg，
                    //二者区别自行百度
                    $(this).val('');
                    return false;
                }

                var reader = new FileReader();
                reader.onload = function(e) {
                    options.imgSrc = e.target.result;
                    cropper = $('.imageBox').cropbox(options);
                }
                reader.readAsDataURL(this.files[0]);
                $(this).val('');
//                this.files = [];
            });
            $('#btnCrop').on('click', function(){
                var img = cropper.getDataURL();
                rsumeUploadzhong(_token, img);
                $('.av-stepfirst').css('display','block');
                $('.av-stepsecond').css('display','none');
                $('#userimg').attr('src',img);

            })
            $('#btnZoomIn').on('click', function(){
                cropper.zoomIn();
                var img = cropper.getDataURL();
                $('#72l22').attr('src',img);
            })
            $('#btnZoomOut').on('click', function(){
                cropper.zoomOut();
                var img = cropper.getDataURL();
                $('#72l22').attr('src',img);
            })

        });
    }
    $(document).ready(function() {
        $(".tabs").tabs(".pane", {
            onClick: function () {
            }
        });
        // $('.input-startday').each(function(index, element) {
        //     $(this).datepicker({
        //         autoclose: true,
        //         format: 'yyyy-mm-dd',
        //         language: 'zh-CN'
        //     }).on('changeDate', function(ev){
        //             // alert(1)
        //         });

        // })
        // 开始时间
        // $('input[name="time_start-0"]').each(function(index,element){
        //     $(this).datepicker({
        //         autoclose : true,
        //         format: 'yyyy-mm-dd',
        //         language: 'zh-CN'
        //     }).on('changeDate',function(e){

        //         var startTime = e.date;
        //         $('input[name="time_end-'+index+'"]').datepicker('setStartDate',startTime);
        //         alert(index)
        //     });
        // })
        // $('input[name="time_end-0"]').each(function(indexed,element){
        //     $(this).datepicker({
        //         autoclose : true,
        //         format: 'yyyy-mm-dd',
        //         language: 'zh-CN'
        //     }).on('changeDate',function(e){
        //         // alert($(this).length);
        //         var endTime = e.date;
        //         $('input[name="time_start-'+indexed+'"]').datepicker('setStartDate',endTime);
        //         alert(indexed)
        //     });
        // })

        $('.time_start').each(function(index,element){
            $(this).datepicker({
                autoclose : true,
                format: 'yyyy-mm-dd',
                language: 'zh-CN'
            }).on('changeDate',function(e){

                var startTime = e.date;
                $('input[name="time_end-'+index+'"]').datepicker('setStartDate',startTime);
            });
        })
        $('.time_end').each(function(indexed,element){
            $(this).datepicker({
                autoclose : true,
                format: 'yyyy-mm-dd',
                language: 'zh-CN'
            }).on('changeDate',function(e){
                // alert($(this).length);
                var endTime = e.date;
                $('input[name="time_start-'+indexed+'"]').datepicker('setEndDate',endTime);
            });
        })




        //结束时间：
    //     $('input[name="time_end-0"]').datepicker({
    //         autoclose : true,
    //         format: 'yyyy-mm-dd',
    //         language: 'zh-CN'
    //     }).on('changeDate',function(e){
    //         var endTime = e.date;
    //         // alert(endTime)
    //         $('input[name="time_start-0"]').datepicker('setEndDate',endTime);
    // })

 })
</script>

</body>

</html>


