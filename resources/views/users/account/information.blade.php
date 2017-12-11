@extends('layouts.layout')

@section('head')
    <title>个人设置-工作网</title>
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/personnel.css') }}"/>
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('css/calendar.css') }}"/>--}}
    <link rel="stylesheet" href="{{ asset('admin/assets/css/datepicker.css') }}" />
@endsection

@section('content')
    <!--个人信息开始-->
    <div id="message">
        <div class="message">
            <div class="message_left">
                <a class="onClick" href="javascript:void(0)"><span></span><span>基本资料</span></a>
                <a href="{{ url('account/avatar') }}"><span></span><span>我的头像</span></a>
                <a href="{{ url('account/oauth') }}"><span></span><span>绑定设置</span></a>
                <a href="{{ url('account/safety') }}"><span></span><span>账户安全</span></a>
                <a href="{{ url('account/proficiency') }}"><span></span><span>擅长领域</span></a>
            </div>
            <div class="message_right">
                <!--基础资料开始-->
                <div class="information">
                    <form id = "form" action="{{ url("/ajax/account/subuserinfo") }}" onsubmit="return false" autocomplete="off">
                        <div>
                            <h3>基础资料</h3>
                        </div>
                        <div class="info-first">
                            <span><b class="importantip">*</b>名号 : </span><input type="text" value="{{ !empty($userData['display_name'])?$userData['display_name']:'' }}" name = "display_name" placeholder="请输入您的名号" />
                        </div>
                        <div class="info-describe">
                            <span><b class="importantip">*</b>状态 :</span>
                            <input type="radio" {{ $userData['occupation'] == 2?'checked':'' }} name="occupation" id="job"  value="2" />
                            <label for="job">职场人士</label>
                            <input type="radio" {{ $userData['occupation'] == 1?'checked':'' }} name="occupation" id="student" value="1" />
                            <label for="student">在校学生</label>

                        </div>
                        <div class="sex">
                            <span><b class="importantip">*</b>性别 :</span>
                            <input type="radio" {{ $userData['gender'] == 1?'checked':'' }} name="gender" id="boy" value="1" /><label for="boy">男</label>
                            <input type="radio" {{ $userData['gender'] == 2?'checked':'' }} name="gender" id="girl" value="2" /><label for="girl">女</label>
                            <input type="radio" {{ $userData['gender'] == 3?'checked':'' }} name="gender" id="screct" value="3" /><label for="screct">保密</label>
                        </div>
                        <div class="info-second">
                            <span><b class="importantip">*</b>个性签名 : </span>
                            <input  type="text" value="{{ !empty($userData['slogan'])?$userData['slogan']:'' }}"  name="slogan" placeholder="请输入您的个性签名" />
                        </div>
                        <div class="info-third">
                            <span><b class="importantip">{{ $userData['occupation'] == 2?'*':'' }}</b>公司 :</span>
                            <input type="text" value="{{ !empty($userData['company'])?$userData['company']:'' }}"  name="company" placeholder="请输入您的公司" />
                        </div>
                        <div class="info-third">
                            <span><b class="importantip">{{ $userData['occupation'] == 2?'*':'' }}</b>职位 :</span>
                            <input type="text" value="{{ !empty($userData['position'])?$userData['position']:'' }}"  name="position" placeholder="请输入您的职位" />
                        </div>
                        <div class="info-third">
                            <span><b class="importantip">{{ $userData['occupation'] == 1?'*':'' }}</b>学校 :</span>
                            <input type="text" value="{{ !empty($userData['school'])?$userData['school']:'' }}"  name="school" placeholder="请输入您的学校" />
                        </div>
                        <div class="info-fourth">
                            <span><b class="importantip">{{ $userData['occupation'] == 1?'*':'' }}</b>专业 :</span>
                            <input type="text" value="{{ !empty($userData['department'])?$userData['department']:'' }}" name="department" placeholder="请输入您的专业" />
                        </div>
                        <div class="info-city">
                            <span>现居 :</span>
                            <select id="province" onchange="changeCity()" name="province">
                                <option value="">请选择省份或直辖市</option>
                                @foreach ($province as $value)
                                    <option  data-text="{{ $value->id }}" {{ $userData['province'] == $value->shortname ? 'selected="selected"':'' }} value="{{ $value->shortname }}">{{ $value->shortname }}</option>
                                @endforeach
                            </select>
                            <select id="city" name="city">
                                {{--<option class="city_se" value="">请选择城市</option>--}}
                                {{--@foreach ($city as $value)--}}
                                    {{--@if($parentid == $value->parentid)--}}
                                        {{--<option style="display: block" {{ $userData['city'] == $value->shortname?'selected':'' }} class="subcity{{ $value->parentid }} gogogrf"  value="{{ $value->shortname }}">{{ $value->shortname }}</option>--}}
                                    {{--@else--}}
                                        {{--<option style="display: none" class="subcity{{ $value->parentid }} gogogrf"  value="{{ $value->shortname }}">{{ $value->shortname }}</option>--}}
                                    {{--@endif--}}
                                {{--@endforeach--}}
                                    @if(empty(!$userData['city']))
                                    <option id="cityname" class="city_se" value="{{$userData['city']}}">{{$userData['city']}}</option>
                                    @else
                                        <option class="city_se" value="">请选择城市</option>
                                    @endif

                            </select>
                        </div>
                        <div class="info-birthday">
                            <span>出生日期  : </span>
                            <input class="input-sm"  type="text" value="{{ !empty($userData['birthday'])?$userData['birthday']:'' }}" name ="birthday">

                        </div>
                        <div class="info-submit">
                            <button onclick="subInfo()">保存</button>
                        </div>
                        {{ csrf_field() }}
                    </form>
                </div>
                <!--基础资料结束-->
            </div>
        </div>
    </div>
    <!--个人信息结束-->
@endsection

@section('javascripts')
    <script src="{{ asset('js/account.js') }}"></script>
    <script src="{{ asset('js/city.js') }}"></script>
    {{--<script src="{{ asset('js/calendar.js') }}"></script>--}}
    <script src="{{ asset('admin/assets/js/date-time/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/date-time/locales/bootstrap-datepicker.zh-CN.js') }}"></script>

    <script>
    window.onload = function () {
        $(function () {
            var birthday = "{{ !empty($userData['birthday']) && $userData['birthday']!= '1000-01-01'?$userData['birthday']:'1990-01-01' }}";
            $( ".input-sm" ).val(birthday);
            $('.input-sm').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm-dd',
                language: 'zh-CN',
            })
//            show datepicker when clicking on the icon
//            .next().on(ace.click_event, function(){
//                $(this).prev().focus();
//            });
//            var calendar = new Calendar();
//            calendar.init({
//                target: $('#calendar'),
//                range: ['2015-3-5', '2015-3-25'],
//                multiple: true,
//                maxdays: 5,
//                overdays: function(a) {
//                    alert('添加已达上限 ' + a + ' 天');
//                }
//            });
//            var c_end2 = $(".input-sm").Calendar({
//                afterSelected: function(obj, date) {
//                    $(obj).blur();
//                }})[0];


        });

    }
</script>
@endsection







