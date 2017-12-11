@extends('layouts.layout')
@section('head')
    <title>技能证书-工作网</title>
@endsection
@section('content')

    <div class="resume resumeMine resume-modify clearfix">
        <!-- 简历主要 开始-->
        <div class="fl modify-maintp">
            <!-- 选择编辑项目 开始 -->
            <ul class="tabs clearfix">
                <li class="fl "><a href="{{url('/resume/persons')}}" class="finish">个人信息</a></li>
                @if($resumestatus['advices']==2)
                    <li class="fl"><a href="{{url('/resume/advices')}}" class="finish">求职意向</a></li>
                @else
                    <li class="fl"><a href="{{url('/resume/advices')}}" >求职意向</a></li>
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
                    <li class="fl"><a href="{{url('/resume/diplomas')}}" class="finish current">技能证书</a></li>
                @else
                    <li class="fl"><a href="{{url('/resume/diplomas')}}" class="current">技能证书</a></li>
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
            <div class="panes modify-mainlist">
                <!--技能证书 开始-->
                <div class="clearfix pane modify-expert" style="display:block">
                    <div class="tp"><span>技能证书</span></div>
                    <!-- 完成状态 开始 -->
                    @if(!empty($diplomas))
                        @foreach($diplomas as $diploma)
                    <div class="cnt">

                            <div class="cnt-inner cnt-finish">
                                <a href="javascript:;" class="btn-modify" onclick="diploma({{$diploma->id}})">编辑</a>

                                    <ul class="clearfix">
                                        @if(!empty($diploma->certificate))
                                        <li class="fl";>证书名称：{{$diploma->certificate}}</li>
                                        @elseif(!empty($diploma->supplementary))
                                            <li class="fl";>证书名称：{{$diploma->supplementary}}</li>
                                        @endif
                                        <li class="fl";>成绩：{{$diploma->achivement}}</li>
                                    </ul>

                            </div>

                        <div class="cnt-inner cnt-mod clearfix hide">
                            <form action="" id="from-diploma{{$diploma->id}}">
                                {{ csrf_field() }}
                                <input type="hidden" name="diplomaid" value="{{$diploma->id}}">
                                <dl class="fl">
                                    <dt class="fl">证书名称：</dt>
                                    <dd class="fl">
                                        <input type="text" readonly  placeholder="请选择证书" class="certificates inpbtn" name="certificate-add" id="certificate-edit{{$diploma->id}}" value="{{$diploma->certificate}}">
                                    </dd>
                                    <!-- 院校名称 选择 开始 -->
                                    <div class="slidmsg academy hide" id="academy">
                                        <div class="academy-city clearfix ">
                                            @foreach($school as $k=>$v)
                                                <a href="javascript:;" data-city='{{ $k }}' class="fl citybtn">{{ $v['name'] }}</a>
                                            @endforeach
                                        </div>
                                        @foreach($school as $k=>$v)
                                            <div class="academy-name city{{ $k }} hide">
                                                <h2>{{ $v['name'] }}</h2>
                                                <div class="btm clearfix">
                                                    @foreach($v['sub'] as $kk=>$vv)
                                                        <a href="javascript:;" class="fl">{{ $vv }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </dl>
                                <dl class="fl diploma" >
                                    <dt class="fl">补充：</dt>
                                    <dd class="fl"><input type="text" name="certificate-cope" id="certificate-editcope{{$diploma->id}}" value="{{$diploma->supplementary}}" placeholder="若左侧无合适选项请在此处填写"></dd>
                                </dl>
                                <dl class="fl">
                                    <dt class="fl">*成绩：</dt>
                                    <dd class="fl"><input type="text" name="achivement" placeholder="请输入成绩" value="{{$diploma->achivement}}" ></dd>
                                </dl>
                                <div  class="btn-del clearfix"><a href="javascript:;" class="fr" onclick="diplomadel({{$diploma->id}})">删除</a></div>
                                <div class="btns">
                                    <input type="submit" value="保存" class="fl btn btn-finish">
                                    <a href="javascript:;" class="fr btn btn-cancel">取消</a>
                                </div>
                            </form>
                        </div>

                    </div>
                        @endforeach
                    <div class="addmore"><span>添加更多技能证书</span></div>
                    <!-- 完成状态 结束 -->
                    @else
                    <!-- 编辑状态 开始 -->
                    <div class="cnt">
                        <div class="cnt-mod clearfix">
                            <form action="" id="from-diplomaadd">
                                {{ csrf_field() }}
                                <dl class="fl">
                                    <dt class="fl">证书名称：</dt>
                                    <dd class="fl">
                                        <input type="text" readonly  placeholder="请选择证书" class="certificates inpbtn" name="certificate-add" id="certificate-add">
                                    </dd>
                                    <!-- 院校名称 选择 开始 -->
                                    <div class="slidmsg academy hide" id="academy">
                                        <div class="academy-city clearfix ">
                                            @foreach($school as $k=>$v)
                                                <a href="javascript:;" data-city='{{ $k }}' class="fl citybtn">{{ $v['name'] }}</a>
                                            @endforeach
                                        </div>
                                        @foreach($school as $k=>$v)
                                            <div class="academy-name city{{ $k }} hide">
                                                <h2>{{ $v['name'] }}</h2>
                                                <div class="btm clearfix">
                                                    @foreach($v['sub'] as $kk=>$vv)
                                                        <a href="javascript:;" class="fl">{{ $vv }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </dl>
                                <dl class="fl diploma">
                                    <dt class="fl">补充：</dt>
                                    <dd class="fl"><input type="text" name="certificate-cope" id="certificate-cope" placeholder="若左侧无合适选项请在此处填写" ></dd>
                                </dl>
                                <dl class="fl">
                                    <dt class="fl">*成绩：</dt>
                                    <dd class="fl"><input type="text" name="achivement" placeholder="请填写成绩"></dd>
                                </dl>
                                <div class="btns">
                                    <input type="submit" value="保存" class="fl btn btn-finish">
                                    <a href="javascript:;" class="fr btn btn-cancel">取消</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- 编辑状态  结束-->
                    @endif
                    <div class="cnt hide cnt-new">
                        <div class="cnt-mod clearfix">
                            <form action="" id="from-diplomaadd">
                                {{ csrf_field() }}
                                <dl class="fl">
                                    <dt class="fl">证书名称：</dt>
                                    <dd class="fl">
                                        <input type="text" readonly  placeholder="请选择证书" class="certificates inpbtn" name="certificate-add" id="certificate-add">
                                    </dd>
                                    <!-- 院校名称 选择 开始 -->
                                    <div class="slidmsg academy hide" id="academy">
                                        <div class="academy-city clearfix ">
                                            @foreach($school as $k=>$v)
                                                <a href="javascript:;" data-city='{{ $k }}' class="fl citybtn">{{ $v['name'] }}</a>
                                            @endforeach
                                        </div>
                                        @foreach($school as $k=>$v)
                                            <div class="academy-name city{{ $k }} hide">
                                                <h2>{{ $v['name'] }}</h2>
                                                <div class="btm clearfix">
                                                    @foreach($v['sub'] as $kk=>$vv)
                                                        <a href="javascript:;" class="fl">{{ $vv }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </dl>
                            <dl class="fl diploma">
                                <dt class="fl">补充：</dt>
                                <dd class="fr"><input type="text" name="certificate-cope" id="certificate-cope" placeholder="若左侧无合适选项请在此处填写"></dd>
                            </dl>
                            <dl class="fl">
                                <dt class="fl">*成绩：</dt>
                                <dd class="fr"><input type="text" name="achivement" placeholder="请填写成绩"></dd>
                            </dl>
                            <div class="btns">
                                <input type="submit" value="保存" class="fl btn btn-finish">
                                <a href="javascript:;" class="fr btn btn-cancel">取消</a>
                            </div>
                            </form>
                        </div>
                    </div>

                </div>
                <!--技能证书 结束-->
            </div>
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
    <link href="{{ asset('css/vcommon.css') }}" rel="stylesheet" />
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
    <script>
        $('.inpbtn').on('click',function(ev){
            var that= $(this);
            showdiplomas(that,ev);
        });
        $('.citybtn').on('click',function(ev){
            var cityid =$(this).attr('data-city');
            var that= $(this);
            city(that,cityid,ev);
        })
    </script>
@endsection
