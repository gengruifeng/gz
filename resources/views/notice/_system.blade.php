@foreach($notifications as $notification)
    <li>
        <div>
            @if($notification->is_new < 1)
                <b class="tipsicon"></b>
            @endif
            <img src="{{asset('images/answers/private_letter_7.gif')}}"/>
        </div>
        <div>
            <div><a href="javascript:void(0)">系统通知</a></div>
            <div>
                <p>{{$notification->content}}</p>
            </div>
            <div>
                <a href="javascript:void(0)">{{$notification->created_at}}</a>
            </div>
        </div>
    </li>
@endforeach