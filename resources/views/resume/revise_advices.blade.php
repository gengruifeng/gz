@extends('layouts.layout')

@section('head')
    <title>求职意向-工作网</title>
@endsection

@section('content')
    <div class="resume resumeMine resume-modify clearfix">
        <!-- 简历主要 开始-->
        <div class="fl modify-maintp">
            <!-- 选择编辑项目 开始 -->
            <ul class="tabs clearfix">
                <li class="fl "><a href="{{url('/resume/persons')}}" class="finish">个人信息</a></li>
                @if($resumestatus['advices']==2)
                    <li class="fl"><a href="{{url('/resume/advices')}}" class="finish current">求职意向</a></li>
                @else
                    <li class="fl"><a href="{{url('/resume/advices')}}" class="current">求职意向</a></li>
                @endif
                @if($resumestatus['educations']==2)
                    <li class="fl"><a href="{{url('/resume/educations')}}" class="finish">教育背景</a></li>
                @else
                    <li class="fl"><a href="{{url('/resume/educations')}}" class="">教育背景</a></li>
                @endif
                @if($resumestatus['experiences']==2)
                    <li class="fl last"><a href="{{url('/resume/experiences')}}" class="finish">个人经历</a></li>
                @else
                    <li class="fl last"><a href="{{url('/resume/experiences')}}" class="">个人经历</a></li>
                @endif
                @if($resumestatus['diplomas']==2)
                    <li class="fl"><a href="{{url('/resume/diplomas')}}" class="finish">技能证书</a></li>
                @else
                    <li class="fl"><a href="{{url('/resume/diplomas')}}" class="">技能证书</a></li>
                @endif
                @if($resumestatus['honors']==2)
                    <li class="fl"><a href="{{url('/resume/honors')}}" class="finish">奖项荣誉</a></li>
                @else
                    <li class="fl"><a href="{{url('/resume/honors')}}" class="">奖项荣誉</a></li>
                @endif
                @if($resumestatus['projects']==2)
                    <li class="fl"><a href="{{url('/resume/projects')}}" class="finish">个人作品</a></li>
                @else
                    <li class="fl"><a href="{{url('/resume/projects')}}" class="">个人作品</a></li>
                @endif
                @if($resumestatus['interests']==2)
                    <li class="fl last"><a href="{{url('/resume/interests')}}" class="finish">兴趣爱好</a></li>
                @else
                    <li class="fl last"><a href="{{url('/resume/interests')}}" class="">兴趣爱好</a></li>
                @endif

            </ul>
            <!-- 选择编辑项目 结束 -->

            <!-- 编辑项目 开始 -->
            <div class="panes modify-mainlist">
                <!--求职意向 开始-->
                <div class="clearfix  modify-job">
                    <div class="tp"><span>求职意向</span></div>
                    <!-- 求职意向完成与便捷 开始 -->
                    @if(!empty($advices))
                        <div class="cnt">

                            <div class="cnt-inner cnt-finish">
                                <a href="javascript:;" class="btn-modify">编辑</a>
                                <ul class="clearfix">
                                    <li class="fl">工作经验：{{$advices->word_period}}</li>
                                    <li class="fl">期望职位：{{$advices->position}}</li>
                                    <li class="fl">职位类型：{{$advices->employment_type}}</li>
                                    <li class="fl">期望城市：{{$advices->city}}</li>
                                    <li class="fl">期望月薪：{{$advices->salary}}</li>
                                    <li class="fl">求职状态：{{$advices->job_type}}</li>
                                </ul>
                            </div>
                            <div class="cnt-inner cnt-mod clearfix hide">
                                <form action="" id="form-advices">
                                    {{ csrf_field() }}
                                    <div class="clearfix">
                                        <dl class="fl">
                                            <dt class="fl">*工作经验： </dt>
                                            <dd class="fr">
                                                <select name="word_period" id="">
                                                    <option value="{{$advices->word_period}}">{{$advices->word_period}}</option>
                                                    <option value="应届毕业生">应届毕业生</option>
                                                    <option value="1年以下">1年以下</option>
                                                    <option value="1-3年">1-3年</option>
                                                    <option value="3-5年">3-5年</option>
                                                    <option value="5-10年">5-10年</option>
                                                    <option value="10年以上">10年以上</option>
                                                </select>
                                            </dd>
                                        </dl>
                                        <dl class="fl">
                                            <dt class="fl">*职位类型：</dt>
                                            <dd class="fr">
                                                <select name="employment_type" id="">
                                                    <option value="{{$advices->employment_type}}">{{$advices->employment_type}}</option>
                                                    <option value="全职">全职</option>
                                                    <option value="兼职">兼职</option>
                                                    <option value="实习">实习</option>
                                                </select>
                                            </dd>
                                        </dl>

                                        <dl class="fl job-pos">
                                            <dt class="fl">*期望职位：</dt>
                                            <dd class="fl">
                                                {{--<input type="text" readonly placeholder="" value="{{$advices->position}}" name="position" class="inpbtn">--}}
                                                <input type="hidden" class="inphide" placeholder="" value="{{$advices->position}}">
                                                <div  class="inpbtn">
                                                    <div class="btn-inner clearfix">
                                                        @foreach($positionarr as $val)
                                                            <span class="btn-add fl" data-cheku="{{$val->id}}">{{$val->name}}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <!-- 职位 开始 -->

                                                <div class="slidmsg hide">
                                                    <ul class="hd clearfix">
                                                        @if(!empty($positionsroot))
                                                            @foreach($positionsroot as $k=>$v)
                                                                @if($k==0)
                                                                    <li class="fl"><a class="active" data-chek="{{$k}}" href="javascript:;">{{ $v->name }}</a></li>
                                                                @else
                                                                    <li class="fl"><a class="" data-chek="{{$k}}" href="javascript:;">{{ $v->name }}</a></li>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                    <!-- panes -->
                                                    <div class="bd">
                                                        @foreach($positions as $k=>$v)
                                                            @if($k==117)
                                                                <div class="clearfix bdinner" style="display: block;">
                                                                    @else
                                                                        <div class="clearfix bdinner">
                                                                            @endif
                                                                            @foreach($v as $kk=>$vv)
                                                                                <dl class="clearfix clearfix">
                                                                                    <dt class="fl">{{ $vv['name'] }}</dt>
                                                                                    <dd class="fl">
                                                                                        @if(!empty($vv['sub']))
                                                                                            <?php $i =1 ?>
                                                                                            @foreach($vv['sub'] as $kkk=>$vvv)
                                                                                                @if(count($vv['sub']) == $i)
                                                                                                    @if(isset($positionarr[$vvv]))
                                                                                                    <a class="fl subthis active" data-chek="{{$kkk}}"  href="javascript:;">{{ $vvv }} </a>
                                                                                                        @else
                                                                                                                <a class="fl subthis" data-chek="{{$kkk}}"  href="javascript:;">{{ $vvv }} </a>
                                                                                                        @endif
                                                                                                @else
                                                                                                        @if(isset($positionarr[$vvv]))
                                                                                                            <a class="fl subthis active" data-chek="{{$kkk}}" href="javascript:;">{{ $vvv }}<span>|</span></a>
                                                                                                        @else
                                                                                                            <a class="fl subthis" data-chek="{{$kkk}}" href="javascript:;">{{ $vvv }}<span>|</span></a>
                                                                                                        @endif


                                                                                                @endif
                                                                                                <?php $i++?>
                                                                                            @endforeach
                                                                                        @endif
                                                                                    </dd>
                                                                                </dl>
                                                                            @endforeach
                                                                        </div>
                                                                        @endforeach
                                                                </div>
                                                    </div>
                                                </div>
                                            </dd>
                                        </dl>
                                        <dl class="fl">
                                            <dt class="fl">*省份：</dt>
                                            <dd class="fr">
                                                <select id="province" onchange="changeCity()" name="province">
                                                    <option value="">请选择省份</option>
                                                    @foreach ($province as $value)
                                                        <option  data-text="{{ $value->id }}" {{ $advices->province == $value->shortname ? 'selected="selected"':'' }} value="{{ $value->shortname }}">{{ $value->shortname }}</option>
                                                    @endforeach
                                                </select>
                                            </dd>
                                        </dl>
                                        <dl class="fl">
                                            <dt class="fl">*期望城市：</dt>
                                            <dd class="fr">
                                                <select id="city" name="city">
                                                    <option id="cityname" class="city_se" value="{{$advices->city}}">{{$advices->city}}</option>
                                                </select>
                                            </dd>
                                        </dl>

                                        <dl class="fl">
                                            <dt class="fl">*期望月薪：</dt>
                                            <dd class="fr">
                                                <select name="salary" id="">
                                                    <option value="{{$advices->salary}}">{{$advices->salary}}</option>
                                                    <option value="2500元以下">2500元以下</option>
                                                    <option value="2500-4000元/月">2500-4000元/月</option>
                                                    <option value="4000-6000元/月">4000-6000元/月</option>
                                                    <option value="6000-8000元/月">6000-8000元/月</option>
                                                    <option value="8000-10000元/月">8000-10000元/月</option>
                                                    <option value="10000-15000元/月">10000-15000元/月</option>
                                                    <option value="15000-20000元/月">15000-20000元/月</option>
                                                    <option value="20000-25000元/月">20000-25000元/月</option>
                                                    <option value="25000元以上">25000元以上</option>
                                                    <option value="面议">面议</option>

                                                </select>
                                            </dd>
                                        </dl>
                                        <dl class="fl">
                                            <dt class="fl">*求职状态：</dt>
                                            <dd class="fr">
                                                <select name="job_type" id="">
                                                    <option value="{{$advices->job_type}}">{{$advices->job_type}}</option>
                                                    <option value="我是应届生">我是应届生</option>
                                                    <option value="我是离职状态，可随时到岗">我是离职状态，可随时到岗</option>
                                                    <option value="我正在考虑换工作，1个月左右可以到岗">我正在考虑换工作，1个月左右可以到岗</option>
                                                    <option value="我是在职状态，有好的机会我会考虑（到岗时间再议）">我是在职状态，有好的机会我会考虑（到岗时间再议）</option>
                                                    <option value="我暂时不考虑换工作">我暂时不考虑换工作</option>
                                                </select>
                                            </dd>
                                        </dl>
                                        <div class="btns clearfix">
                                            <input type="submit" value="保存" class="fl btn btn-finish">
                                            <a href="javascript:;" class="fr btn btn-cancel">取消</a>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- 添加更多 结束 -->
                        </div>
                @else
                    <!-- 编辑状态 开始 -->
                        <div class="cnt">
                            <div class="cnt-mod clearfix">
                                <form action="" id="form-advices">
                                    {{ csrf_field() }}
                                    <div class="clearfix">
                                        <dl class="fl">
                                            <dt class="fl">*工作经验： </dt>
                                            <dd class="fr">

                                                <select name="word_period" id="">
                                                    <option value="">请选择</option>
                                                    <option value="应届毕业生">应届毕业生</option>
                                                    <option value="1年以下">1年以下</option>
                                                    <option value="1-3年">1-3年</option>
                                                    <option value="3-5年">3-5年</option>
                                                    <option value="5-10年">5-10年</option>
                                                    <option value="10年以上">10年以上</option>
                                                </select>
                                            </dd>
                                        </dl>
                                        <dl class="fl">
                                            <dt class="fl">*职位类型：</dt>
                                            <dd class="fr">
                                                <select name="employment_type" id="">
                                                    <option value="">请选择</option>
                                                    <option value="全职">全职</option>
                                                    <option value="兼职">兼职</option>
                                                    <option value="实习">实习</option>
                                                </select>
                                            </dd>
                                        </dl>
                                        <dl class="fl job-pos">
                                            <dt class="fl">*期望职位：</dt>
                                            <dd class="fl">
                                                <input type="hidden" class="inphide" >
                                                <div  class="inpbtn">
                                                    <div class="btn-inner clearfix">
                                                    </div>
                                                </div>
                                                <!-- 职位 开始 -->

                                                <div class="slidmsg hide">
                                                    <ul class="hd clearfix">
                                                        @if(!empty($positionsroot))
                                                            @foreach($positionsroot as $k=>$v)
                                                                @if($k==0)
                                                                    <li class="fl"><a class="active" href="javascript:;">{{ $v->name }}</a></li>
                                                                @else
                                                                    <li class="fl"><a class="" href="javascript:;">{{ $v->name }}</a></li>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                    <!-- panes -->
                                                    <div class="bd">
                                                        @foreach($positions as $k=>$v)
                                                            @if($k==117)
                                                                <div class="clearfix bdinner" style="display: block;">
                                                                    @else
                                                                        <div class="clearfix bdinner">
                                                                            @endif
                                                                            @foreach($v as $kk=>$vv)
                                                                                <dl class="clearfix clearfix">
                                                                                    <dt class="fl">{{ $vv['name'] }}</dt>
                                                                                    <dd class="fl">
                                                                                        @if(!empty($vv['sub']))
                                                                                            <?php $i =1 ?>
                                                                                            @foreach($vv['sub'] as $kkk=>$vvv)
                                                                                                @if(count($vv['sub']) == $i)
                                                                                                    <a class="fl subthis" data-chek="{{$kkk}}"  href="javascript:;">{{ $vvv }} </a>
                                                                                                @else
                                                                                                    <a class="fl subthis" data-chek="{{$kkk}}" href="javascript:;">{{ $vvv }}<span>|</span></a>
                                                                                                @endif
                                                                                                <?php $i++?>
                                                                                            @endforeach
                                                                                        @endif
                                                                                    </dd>
                                                                                </dl>
                                                                            @endforeach
                                                                        </div>
                                                                        @endforeach
                                                                </div>
                                                    </div>
                                                </div>
                                            </dd>
                                        </dl>
                                        <dl class="fl">
                                            <dt class="fl">*省份：</dt>
                                            <dd class="fr">
                                                <select id="province" onchange="changeCity(this)" name="province">
                                                    <option value="">请选择省份</option>
                                                    @foreach ($province as $value)
                                                        <option  data-text="{{ $value->id }}"  value="{{ $value->shortname }}">{{ $value->shortname }}</option>
                                                    @endforeach
                                                </select>
                                            </dd>
                                        </dl>
                                        <dl class="fl">
                                            <dt class="fl">*期望城市：</dt>
                                            <dd class="fr">
                                                <select id="city" name="city">
                                                    <option class="city_se" value="">请选择城市</option>
                                                </select>
                                            </dd>
                                        </dl>

                                        <dl class="fl">
                                            <dt class="fl">*期望月薪：</dt>
                                            <dd class="fr">
                                                <select name="salary" id="">
                                                    <option value="" >请选择</option>
                                                    <option value="2500元以下">2500元以下</option>
                                                    <option value="2500-4000元/月">2500-4000元/月</option>
                                                    <option value="4000-6000元/月">4000-6000元/月</option>
                                                    <option value="6000-8000元/月">6000-8000元/月</option>
                                                    <option value="8000-10000元/月">8000-10000元/月</option>
                                                    <option value="10000-15000元/月">10000-15000元/月</option>
                                                    <option value="15000-20000元/月">15000-20000元/月</option>
                                                    <option value="20000-25000元/月">20000-25000元/月</option>
                                                    <option value="25000元以上">25000元以上</option>
                                                    <option value="面议">面议</option>

                                                </select>
                                            </dd>
                                        </dl>
                                        <dl class="fl">
                                            <dt class="fl">*求职状态：</dt>
                                            <dd class="fr">
                                                <select name="job_type" id="">
                                                    <option value="">请选择</option>
                                                    <option value="我是应届生">我是应届生</option>
                                                    <option value="我是离职状态，可随时到岗">我是离职状态，可随时到岗</option>
                                                    <option value="我正在考虑换工作，1个月左右可以到岗">我正在考虑换工作，1个月左右可以到岗</option>
                                                    <option value="我是在职状态，有好的机会我会考虑（到岗时间再议）">我是在职状态，有好的机会我会考虑（到岗时间再议）</option>
                                                    <option value="我暂时不考虑换工作">我暂时不考虑换工作</option>
                                                </select>
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="btns clearfix">
                                        <input type="submit" value="保存" class="fl btn btn-finish">
                                        <a href="javascript:;" class="fr btn btn-cancel">取消</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- 编辑状态  结束-->
                @endif
                <!-- 求职意向完成与便捷 结束 -->


                </div>
                <!--求职意向 结束-->
            <!-- 编辑项目 开始 -->
        </div>
        <!--简历主要 结束-->
        <!-- 简历提示 开始-->
        <div class="fr side">
            <div class="tp">
                <p class="step">完成以下步骤：</p>
                <p class="steptip">快速生成A4简历，适合打印和邮件发送</p>
                <div class="process clearfix">
                    <span class="fl active"></span>
                    <span class="fl line active"></span>
                    <span class="fl"></span>
                    <span class="fl line"></span>
                    <span class="fl"></span>
                </div>
                <div class="processtip">
                    <span class="active">1.编辑简历</span>
                    <span>2.选择模板</span>
                    <span>3.保存简历</span>
                </div>

            </div>
            <a href="javascript:;"  onclick="resumeselect()" class="sub-btn">生成A4简历</a>
        </div>
        <!--简历提示 结束-->
    </div>
@endsection
@section('stylesheets')
    <link href="{{ asset('css/resume.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/resum-modify.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/assets/css/datepicker.css') }}" rel="stylesheet" />
@endsection
@section('javascripts')
    <!--脚部结束-->
    <script src="{{ asset('admin/assets/js/date-time/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/date-time/locales/bootstrap-datepicker.zh-CN.js') }}"></script>
    <script src="{{ asset('js/resumeModify.js')}}"></script>
    <script src="{{ asset('admin/assets/js/ace/ace.js')}}"></script>
    <script src="{{ asset('js/city.js')}}"></script>
    <script>
        // 选择职位显示隐藏
        $('.inpbtn').on('click',function(ev){
            $(this).addClass('active');
            $(this).closest('.cnt-mod').find('.slidmsg').show();
            var oEvent=ev||event;
            oEvent.cancelBubble=true;
            oEvent.stopPropagation();
        });
        //选择职位
        $('.slidmsg .bd a').on('click',function(ev){
            var s = $(this).text();
            s=s.substring(0,s.length-1);
            var btnInner=$(this).closest('.cnt-mod').find('.btn-inner');
            if(btnInner.find('.btn-add').length<3&&!$(this).hasClass('active')){
                $(this).addClass('active');
                var skus=$(this).attr('data-chek');
                btnInner.append('<span class="btn-add fl" data-cheku="'+skus+'">'+s+'</span>');
                var btns=btnInner.find('.btn-add');
                var strs='';
                for(var i =0; i<btns.length;i++){
                    strs += ($(btns[i]).html()+';');
                }
                $(this).closest('.cnt-mod').find('.inphide').val(strs);

            }else if($(this).hasClass('active')){
                var btna = $(this).closest('.cnt-mod').find('.btn-add');
                var h=$(this).attr('data-chek');
                for(var z =0; z<btna.length;z++){
                    if($(btna[z]).attr('data-cheku')==h){
                        $(btna[z]).remove();
                    }
                }
                $(this).removeClass('active');
            }else {
                dialogcom_warn('最多选三个哦!');
            }
            var oEvent=ev||event;
            oEvent.cancelBubble=true;
            oEvent.stopPropagation();
        });
        // 删除选择的职位
        $('.btn-inner').on('click','.btn-add',function(ev){
            var that=$(this).parent();
            var unumb =$(this).attr("data-cheku");
            var aob = $(this).closest('.cnt-mod').find('.slidmsg').find('a');
            for(var y = 0;y<aob.length;y++){
                if($(aob[y]).attr('data-chek')==unumb){
                    $(aob[y]).removeClass('active');
                }
            }
            $(this).remove();
            var btns=that.closest('.cnt-mod').find('.btn-add');
            var strs='';
            for(var i =0; i<btns.length;i++){
                strs += ($(btns[i]).html()+';');
            }
            that.closest('.cnt-mod').find('.inphide').val(strs);
            ev.stopPropagation();
        });

        // 选择职位 切换城市
        $('.slidmsg .hd li').on('click',function(ev){
            var index=$(this).index();
            $(this).closest('.hd').find('a').removeClass('active');
            $(this).find('a').addClass('active');
            $(this).closest('.slidmsg').find('.bdinner').hide();
            $(this).closest('.slidmsg').find('.bdinner').eq(index).show();
            var oEvent=ev||event;
            oEvent.cancelBubble=true;
            oEvent.stopPropagation();
        });
        // 点其它地方障出层消失
        $(document).on('click',function(){
            $('.inpbtn').removeClass('active');
            if($(this).find('.slidmsg').length>0){
                $('.slidmsg').hide();
            }
            $('.inpbtn').removeClass('active');
        })
    </script>
@endsection
