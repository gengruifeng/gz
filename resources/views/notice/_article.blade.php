@foreach($notifications as $notification)
    @if($notification->show_type<4)
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
                        @if($notification->show_type==1)
                            评论了
                        @elseif($notification->show_type==2)
                            收藏了
                        @else
                            赞了
                        @endif
                        我的文章 :</p>
                </div>
                <div>
                    <p><a style="color: #f87e6a;" href="{{asset('articles/'.$notification->articleId)}}">{{$notification->articleSubject}}</a></p>
                </div>
                <div>
                    <a href="javascript:void(0)">{{$notification->created_at}}</a>
                </div>
            </div>
        </li>
    @else
        <li class="touch">

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
                    <p style="line-height: 20px"><a href="{{url('profile/'.$notification->userId)}}">{{$notification->name}}</a>
                        @if($notification->show_type ==4)
                            在文章<a style="color: #f87e6a;" href="{{url('articles/'.$notification->articleId)}}">{{$notification->articleSubject}}</a>的评论中提到了我。
                        @elseif($notification->show_type ==5)
                            小编很愉快的告诉您，您的文章 <a style="color: #f87e6a;" href="{{url('articles/'.$notification->articleId)}}">{{$notification->articleSubject}}</a>已经通过审核，文章在网页上可正常显示。
                        @elseif($notification->show_type ==6)
                            小编很遗憾的告诉您，您的文章<a style="color: #f87e6a;" href="{{url('articles/'.$notification->articleId)}}">{{$notification->articleSubject}}</a>未能审核通过，原因如下：<span style="color: rgb(248,124,106)">{{$notification->content}}</span>
                        @elseif($notification->show_type ==7)
                            小编很遗憾的告诉您，您的文章<a style="color: #f87e6a;" href="{{url('articles/'.$notification->articleId)}}">{{$notification->articleSubject}}</a>已被小编删除，原因如下：<span style="color: rgb(248,124,106)">{{$notification->content}}</span>
                        @endif
                    </p>
                </div>
                <div>
                    <a href="javascript:void(0)">{{$notification->created_at}}</a>
                </div>
            </div>
        </li>
    @endif
@endforeach