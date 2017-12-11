@extends('layouts.layout')
@section('head')
    <title>个人作品-工作网</title>
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
                    <li class="fl"><a href="{{url('/resume/projects')}}" class="finish current">个人作品</a></li>
                @else
                    <li class="fl"><a href="{{url('/resume/projects')}}" class="current">个人作品</a></li>
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
                <!--个人作品 开始-->
                <div class="clearfix pane modify-work" style="display:block">
                    <div class="tp"><span>个人作品</span></div>
                    <!-- 完成状态 开始 -->
                    @if(!empty($projects))
                        @foreach($projects as $project)
                    <div class="cnt">
                            <div class="cnt-inner cnt-finish">
                                <a href="javascript:;" class="btn-modify" onclick="project({{$project->id}})">编辑</a>
                                    <ul class="clearfix">
                                        <li class="fl">完成时间：{{str_replace('-','.',$project->worked_at)}}</li>
                                        <li class="fl">作品名称：{{$project->title}}</li>
                                        @if(!empty($project->subtitle))
                                        <li class="fl">副标题：{{$project->subtitle}}</li>
                                        @endif
                                    </ul>
                                    <!-- 长描述开始 -->

                                    <dl>
                                        <dt>作品描述：</dt>
                                        <dd>{{$project->description}}</dd>
                                    </dl>

                            <!-- 长描述 结束 -->
                            </div>
                        <div class="cnt-inner cnt-mod clearfix hide">
                            <form action=""  id="form-project{{$project->id}}">
                                {{ csrf_field() }}
                                <input type="hidden" name="projectid" value="{{$project->id}}">
                                <div class="clearfix">
                                    <dl class="fl">
                                        <dt class="fl">*完成时间：</dt>
                                        <dd class="fr"><input class="input-startday" type="text" name="worked_at" autocomplete="off" placeholder="请选择" value="{{$project->worked_at}}"></dd>
                                    </dl>
                                    <dl class="fl">
                                        <dt class="fl">*作品名称</dt>
                                        <dd class="fr"><input type="text" name="title" placeholder="请输入您的作品名称，2-100个字" value="{{$project->title}}"></dd>
                                    </dl>
                                    <dl class="fl">
                                        <dt class="fl">副标题</dt>
                                        <dd class="fr"><input type="text" name="subtitle" placeholder="请输入您的作品类别，2-100个字" value="{{$project->subtitle}}"></dd>
                                    </dl>
                                </div>
                                <div class="inptext">
                                    <p>*作品描述：</p>
                                    <div class="inptext-wrap">
                                        <textarea name="description" id="" placeholder="请填写，可填写包括文章/图片/词曲/视频/设计/程序等个人作品，需写清浏览链接，或在附页展示（2-1000字符）">{{$project->description}}</textarea>
                                    </div>
                                </div>
                                <div  class="btn-del clearfix"><a href="javascript:;" class="fr" onclick="projectdel({{$project->id}})">删除</a></div>
                                <div class="btns">
                                    <input type="submit" value="保存" class="fl btn btn-finish">
                                    <a href="javascript:;" class="fr btn btn-cancel">取消</a>
                                </div>
                            </form>
                        </div>
                    </div>
                        @endforeach
                    <div class="addmore"><span>添加更多个人作品</span></div>
                    <!-- 完成状态 结束 -->
                     @else
                    <!-- 没有时 开始 -->
                    <div class="cnt">
                        <div class="cnt-mod clearfix">
                            <form action="" id="form-projectadd">
                                {{ csrf_field() }}
                                <div class="clearfix">
                                    <dl class="fl">
                                        <dt class="fl">*完成时间：</dt>
                                        <dd class="fr"><input class="input-startday" type="text" name="worked_at" autocomplete="off" placeholder="请选择"></dd>
                                    </dl>
                                    <dl class="fl">
                                        <dt class="fl">*作品名称</dt>
                                        <dd class="fr"><input type="text" name="title" placeholder="请输入您的作品名称，2-100个字"></dd>
                                    </dl>
                                    <dl class="fl">
                                        <dt class="fl">副标题</dt>
                                        <dd class="fr"><input type="text" name="subtitle" placeholder="请输入您的作品类别，2-100个字"></dd>
                                    </dl>
                                </div>
                                <div class="inptext">
                                    <p>*作品描述：</p>
                                    <div class="inptext-wrap">
                                        <textarea name="description" id="" placeholder="请填写，可填写包括文章/图片/词曲/视频/设计/程序等个人作品，需写清浏览链接，或在附页展示（2-1000字符）"></textarea>
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
                        <div class="cnt-mod clearfix">
                            <form action="" id="form-projectadd">
                                {{ csrf_field() }}
                                <div class="clearfix">
                                    <dl class="fl">
                                        <dt class="fl">*完成时间：</dt>
                                        <dd class="fr"><input type="text" class="input-startday" autocomplete="off" name="worked_at" placeholder="请选择"></dd>
                                    </dl>
                                    <dl class="fl">
                                        <dt class="fl">*作品名称</dt>
                                        <dd class="fr"><input type="text" name="title" placeholder="请输入您的作品名称，2-100个字"></dd>
                                    </dl>
                                    <dl class="fl">
                                        <dt class="fl">副标题</dt>
                                        <dd class="fr"><input type="text" name="subtitle" placeholder="请输入您的作品类别，2-100个字"></dd>
                                    </dl>
                                </div>
                                <div class="inptext">
                                    <p>*作品描述：</p>
                                    <div class="inptext-wrap">
                                        <textarea name="description" id="" placeholder="请填写，可填写包括文章/图片/词曲/视频/设计/程序等个人作品，需写清浏览链接，或在附页展示（2-1000字符）"></textarea>
                                    </div>
                                </div>
                                <div class="btns">
                                    <input type="submit" value="保存" class="fl btn btn-finish">
                                    <a href="javascript:;" class="fr btn btn-cancel">取消</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- 没有时  结束-->
                </div>
                <!--个人作品 结束-->
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
    <link href="{{ asset('admin/assets/css/datepicker.css') }}" rel="stylesheet" />
@endsection
@section('javascripts')
    <!--脚部结束-->
    <script src="{{ asset('admin/assets/js/date-time/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/date-time/locales/bootstrap-datepicker.zh-CN.js') }}"></script>
    <script src="{{ asset('js/resumeModify.js')}}"></script>
    <script src="{{ asset('admin/assets/js/ace/ace.js')}}"></script>
@endsection
