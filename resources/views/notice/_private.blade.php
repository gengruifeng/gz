@foreach($notifications as $notification)
    <li id="privateMsg{{$notification->id}}">
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
            <div><a href="javascript:void(0)">{{$notification->name}}</a></div>
            <div>
                <p class ='privateLettersChange'  onclick="dialogmessage({{$notification->id}})">{{$notification->content}}</p>
            </div>
            <div>
                <a href="javascript:void(0)">{{$notification->created_at}}</a>
                <a href="javascript:void(0)" onclick="dialogmessage({{$notification->id}})">{{$notification->count}}条对话</a>
            </div>
        </div>
        <a href="javascript:void(0)" onclick="delDialog({{$notification->id}})"></a>
    </li>
@endforeach