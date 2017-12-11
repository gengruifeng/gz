<div class="object-card">
    <dl class="clearfix">
        <dt class="fl">
            <img src="{{url("avatars/60/".$userinfo->avatar."")}}" alt="">
        </dt>
        <dd class="fl">
            <h3>
                <a href="/profile/{{$userinfo->id}}">{{$userinfo->display_name}}</a>
            </h3>
                <p>{{$userinfo->userstatus['corporate']}}</p>
                <p>{{$userinfo->userstatus['position']}}</p>
        </dd>
    </dl>
    @if($userinfo->isstared==2)
    <ul class="clearfix alertPersonMsgHideBtm">

        <li class="fl">
            <a id="myCenter_sendLetter" onclick="isLogin('sendLetter({{$userinfo->id}})',2)" href="javascript:void(0)">私信</a></li><li class="fl">
            <a id="myCenter_quiz" href="javascript:void(0)" onclick="isLogin('quiz({{$userinfo->id}})',2)">向Ta提问</a></li><li class="fr ">
            @if($userinfo->isusertared==1)
            <a href="javascript:void(0)" onclick="isLogin('addAttention({{$userinfo->id}})',2)" id="cardgz{{$userinfo->id}}" data-text="{{$userinfo->display_name}}" class="btn btn-attion">关注</a>
            @elseif($userinfo->isusertared==2)
           <a href='javascript:void(0)' onclick='deleteAttention({{$userinfo->id}})' id='cardgz"{{$userinfo->id}}' data-text='"+jsonobj.display_name+"'  class="btn btn-attion-cancel">取消关注</a>
            @endif
        </li>
    </ul>
    @endif
    </div>
