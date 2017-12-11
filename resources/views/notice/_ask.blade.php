@foreach($notifications as $notification)
    <li class="comment">
        <div>
            @if($notification->is_new == 1)
                <b class="tipsicon"></b>
            @endif
            @if(!empty($notification->avatar))
                <img src="{{ url('/avatars/60/'.$notification->avatar)}}"/>
            @else
                <img src="{{url('/avatars/60/head.png')}}"/>
            @endif
        </div>
        <div>
            <div>
                <p><a href="{{url('profile/'.$notification->userId)}}">{{$notification->name}}</a>
                    @if($notification->show_type == 21)
                        回答了我的问题:
                    @elseif($notification->show_type == 22)
                        编辑了我的问题:
                    @elseif($notification->show_type == 23)
                        删除了我的问题:
                    @elseif($notification->show_type == 24)
                        回答了我关注的问题:
                    @elseif($notification->show_type == 25)
                        邀请我回答问题:
                    @elseif($notification->show_type == 26)
                        赞同了我的回答:
                    @elseif($notification->show_type == 27)
                        评论了我的回答:
                    @elseif($notification->show_type == 28)
                        回复了我的评论:
                    @elseif($notification->show_type == 29)
                        关注了我
                    @endif
                </p>
            </div>
            <div>
                @if(in_array($notification->show_type,array(21,22,24,25)))
                    <p>
                        <a style="color: #f87e6a" href="{{url('questions/'.$notification->question_id)}}">{{$notification->question_subject}}</a>
                    </p>
                @elseif(in_array($notification->show_type,array(23)))
                    <p>
                        <a style="color: #f87e6a" href="#">{!! $notification->question_subject !!}</a>
                    </p>
                @elseif(in_array($notification->show_type,array(26,27)))
                    <p>
                        <a style="color: #f87e6a" href="{{url('questions/'.$notification->question_id)}}">{!! $notification->answer_detail !!}</a>
                    </p>
                @elseif($notification->show_type == 28)
                    <p>
                        <a style="color: #f87e6a" href="{{url('questions/'.$notification->question_id)}}">{!! $notification->comment_content !!}</a>

                    </p>
                @endif
            </div>
            <div>
                <a href="javascript:void(0)">{{$notification->created_at}}</a>
            </div>
        </div>
    </li>
@endforeach