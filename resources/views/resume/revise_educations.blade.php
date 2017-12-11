@extends('layouts.layout')
@section('head')
    <title>教育背景-工作网</title>
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
                    <li class="fl"><a href="{{url('/resume/educations')}}" class="finish current">教育背景</a></li>
                @else
                    <li class="fl"><a href="{{url('/resume/educations')}}" class="current">教育背景</a></li>
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
                <!--教育背景 开始-->
                <div class="clearfix pane modify-education" style="display:block">
                    <div class="tp"><span>教育背景</span></div>
                    @if(!empty($educations))
                        @foreach($educations  as $educationkey=>$education)
                            <div class="cnt">
                                <div class="cnt-inner cnt-finish">

                                <a href="javascript:;" class="btn-modify" onclick="education({{$education->id}})" >编辑</a>
                                    <ul class="clearfix">
                                        <li class="fl">院校名称：{{$education->school}}</li>
                                        <li class="fl">所学专业：{{$education->department}}</li>
                                        <li class="fl">入学时间：{{str_replace('-','.',$education->enrolled)}}</li>
                                        <li class="fl">毕业时间：{{str_replace('-','.',$education->graduated)}}</li>
                                        <li class="fl">学历：{{$education->education}}</li>
                                    </ul>
                                    <!-- 长描述开始 -->
                                    <dl>
                                        @if(!empty($education->success))
                                        <dt>取得成就：</dt>
                                            <dd>{{$education->success}}</dd>                                                                                  @endif

                                    </dl>

                            <!-- 长描述 结束 -->
                            </div>
                                <div class="cnt-inner cnt-mod clearfix hide">
                                        <form action="" id="from-education{{$education->id}}">
                                            {{ csrf_field() }}
                                            <input type="hidden" value="{{$education->id}}" name="educationid">
                                            <div class="clearfix">
                                                <dl class="clearfix school">
                                                    <dt class="fl">院校名称：</dt>
                                                    <dd class="fl">
                                                        <input type="text" readonly  placeholder="请填写你的院校名称" value="{{$education->school}}" class="school_name inpbtn" name="school_name" id="school_name">
                                                    </dd>
                                                    <!-- 院校名称 选择 开始 -->
                                                    <div class="slidmsg academy hide" id="academy">
                                                        <div class="academy-city clearfix ">
                                                            @foreach($school as $k=>$v)
                                                                <a href="javascript:;" data-city="{{ $k }}" class="fl citybtn">{{ $v['name'] }}</a>
                                                            @endforeach
                                                        </div>
                                                        @foreach($school as $k=>$v)
                                                            <div class="academy-name city{{ $k }} hide">
                                                                <h2>{{ $v['name'] }}</h2>
                                                                <input class="data-city" type="text" data-city="{{ $k }}"  name="seachschool" placeholder="搜索你的院校名称" >
                                                                <div class="btm clearfix">
                                                                    @foreach($v['sub'] as $kk=>$vv)
                                                                        <a href="javascript:;" class="fl">{{ $vv }}</a>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </dl>
                                                <dl class="fl">
                                                    <dt class="fl">*入学时间：</dt>
                                                    <dd class="fr time"><input class="input-startday" name="time_start" autocomplete="off" type="text" placeholder="入学时间" value="{{$education->enrolled}}"></dd>
                                                </dl>
                                                <dl class="fl">
                                                    <dt class="fl">*毕业时间：</dt>
                                                    <dd class="fr time"><input class="input-startday" type="text" autocomplete="off" placeholder="毕业时间" name="time_end" value="{{$education->graduated}}"></dd>
                                                </dl>
                                                <dl class="fl">
                                                    <dt class="fl">*所学专业：</dt>
                                                    <dd class="fr"><input name="expert_name" type="text" placeholder="如:计算机与科学" value="{{$education->department}}"></dd>
                                                </dl>
                                                <dl class="fl">
                                                    <dt class="fl">*学历：</dt>
                                                    <dd class="fr">
                                                        <select name="level" title="请选择您的学历">
                                                            <option value="{{$education->education}}">{{$education->education}}</option>
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
                                            <div class="inptext">
                                                <p>取得成就：</p>
                                                <div class="inptext-wrap">
                                                    <textarea name="success" id="" placeholder="在校期间最自豪的事，有什么重大的课题研究，是否参与社团，获得过什么奖励和荣誉（2-1000字）">{{$education->success}}</textarea>
                                                </div>
                                            </div>
                                            <div  class="btn-del clearfix"><a href="javascript:;" class="fr" onclick="educationdel({{$education->id}})">删除</a>
                                            </div>
                                            <div class="btns">
                                                <input type="submit" value="保存" class="fl btn btn-finish">
                                                <a href="javascript:;" class="fr btn btn-cancel">取消</a>
                                            </div>
                                        </form>
                        </div>
                            </div>
                        @endforeach
                        <div class="addmore"><span>添加更多教育背景</span></div>
                    <!-- 没有个人信息时 开始  -->
                    @else
                        <div class="cnt">
                        <div class="cnt-inner cnt-mod clearfix">
                            <form action="" id="from-educationadd">
                                {{ csrf_field() }}

                                <div class="clearfix">
                                    <dl class="clearfix school">
                                        <dt class="fl">院校名称：</dt>
                                        <dd class="fl">
                                            <input type="text" readonly  placeholder="请选择你的院校" class="school_name inpbtn" name="school_name" id="school_name">
                                        </dd>
                                        <!-- 院校名称 选择 开始 -->
                                        <div class="slidmsg academy hide" id="academy">
                                            <div class="academy-city clearfix ">
                                                @foreach($school as $k=>$v)
                                                    <a href="javascript:;" data-city="{{ $k }}" class="fl citybtn">{{ $v['name'] }}</a>
                                                @endforeach
                                            </div>
                                            @foreach($school as $k=>$v)
                                                <div class="academy-name city{{ $k }} hide">
                                                    <h2>{{ $v['name'] }}</h2>
                                                    <input class="data-city" type="text" data-city="{{ $k }}"  name="seachschool" placeholder="搜索你的院校名称" >
                                                    <div class="btm clearfix">
                                                        @foreach($v['sub'] as $kk=>$vv)
                                                            <a href="javascript:;" class="fl">{{ $vv }}</a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </dl>
                                    <dl class="fl">
                                        <dt class="fl">*入学时间：</dt>
                                        <dd class="fr time"><input class="input-startday" autocomplete="off" name="time_start" type="text" placeholder="入学时间">
                                    </dl>
                                    <dl class="fl">
                                        <dt class="fl">*毕业时间：</dt>
                                        <dd class="fr time"><input class="input-startday" autocomplete="off" name="time_end" type="text" placeholder="毕业时间"></dd></dd>
                                    </dl>
                                    <dl class="fl">
                                        <dt class="fl">*所学专业：</dt>
                                        <dd class="fr"><input type="text" name="expert_name" placeholder="如:计算机与科学"></dd>
                                    </dl>
                                    <dl class="fl">
                                        <dt class="fl">*学历：</dt>
                                        <dd class="fr">
                                            <select name="level" title="请选择您的学历">
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

                                <div class="inptext">
                                    <p>取得成就：</p>
                                    <div class="inptext-wrap">
                                        <textarea  name="success"  id="" placeholder="在校期间最自豪的事，有什么重大的课题研究，是否参与社团，获得过什么奖励和荣誉（2-1000字）"></textarea>
                                    </div>
                                </div>
                                <div class="btns">
                                    <input type="submit" value="保存" class="fl btn btn-finish">
                                    <a href="javascript:;" class="fr btn btn-cancel">取消</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                    <div class="cnt hide cnt-new">
                        <div class="cnt-inner cnt-mod clearfix">
                            <form action="" id="from-educationadd">
                                {{ csrf_field() }}
                               <div class="clearfix">
                                   <dl class="clearfix school">
                                       <dt class="fl">院校名称：</dt>
                                       <dd class="fl">
                                           <input type="text" readonly  placeholder="请选择你的院校" class="school_name inpbtn" name="school_name" id="school_name">
                                       </dd>
                                       <!-- 院校名称 选择 开始 -->
                                       <div class="slidmsg academy hide" id="academy">
                                           <div class="academy-city clearfix ">
                                               @foreach($school as $k=>$v)
                                                   <a href="javascript:;"  data-city="{{ $k }}" class="fl citybtn">{{ $v['name'] }}</a>
                                               @endforeach
                                           </div>
                                           @foreach($school as $k=>$v)
                                               <div class="academy-name city{{ $k }} hide">
                                                   <h2>{{ $v['name'] }}</h2>
                                                   <input class="data-city" type="text" data-city="{{ $k }}"  name="seachschool" placeholder="搜索你的院校名称" >
                                                   <div class="btm clearfix">
                                                       @foreach($v['sub'] as $kk=>$vv)
                                                           <a href="javascript:;" class="fl">{{ $vv }}</a>
                                                       @endforeach
                                                   </div>
                                               </div>
                                           @endforeach
                                       </div>
                                   </dl>
                                   <dl class="fl">
                                       <dt class="fl">*入学时间：</dt>
                                       <dd class="fr time"><input class="input-startday" name="time_start" autocomplete="off" type="text" placeholder="入学时间">
                                   </dl>
                                   <dl class="fl">
                                       <dt class="fl">*毕业时间：</dt>
                                       <dd class="fr time"><input class="input-startday" name="time_end" type="text" autocomplete="off" placeholder="毕业时间"></dd></dd>
                                   </dl>
                                   <dl class="fl">
                                       <dt class="fl">*所学专业：</dt>
                                       <dd class="fr"><input type="text" name="expert_name" placeholder="如:计算机与科学"></dd>
                                   </dl>
                                   <dl class="fl">
                                       <dt class="fl">*学历：</dt>
                                       <dd class="fr">
                                           <select name="level" title="请选择您的学历">
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
                                <div class="inptext">
                                    <p>取得成就：</p>
                                    <div class="inptext-wrap">
                                        <textarea  name="success"  id="" placeholder="在校期间最自豪的事，有什么重大的课题研究，是否参与社团，获得过什么奖励和荣誉（2-1000字）"></textarea>
                                    </div>
                                </div>
                                <div class="btns">
                                    <input type="submit" value="保存" class="fl btn btn-finish">
                                    <a href="javascript:;" class="fr btn btn-cancel">取消</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- 没有个人信息时 结束 -->
                </div>
                <!--教育背景 结束-->
            </div>
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
    <link href="{{ asset('css/autocomplete/jquery-ui.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/assets/css/datepicker.css') }}" rel="stylesheet" />
@endsection
@section('javascripts')
    <!--脚部结束-->
    <script src="{{ asset('js/typeahead.bundle.js')}}"></script>
    <script src="{{ asset('js/search.js')}}"></script>
    <script src="{{ asset('js/handlebars.js')}}"></script>
    <script src="{{ asset('admin/assets/js/date-time/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/date-time/locales/bootstrap-datepicker.zh-CN.js') }}"></script>
    <script src="{{ asset('js/resumeModify.js')}}"></script>
    <script src="{{ asset('css/autocomplete/jquery-ui.js')}}"></script>
    <script src="{{ asset('admin/assets/js/ace/ace.js')}}"></script>
    <script>
        $('.inpbtn').on('click',function(event){
            var that= $(this);
            showschool(that,event);
        });
        $('.citybtn').on('click',function(event){
            var cityid =$(this).attr('data-city');
            var that= $(this);
            city(that,cityid,event);
        })
    </script>
@endsection
