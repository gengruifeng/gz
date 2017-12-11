
    @foreach ($askinfo as $askList)
        <li class="clearfix">
            <div>
                <a href="{{url("profile/".$askList['askuid']."")}}" data-text="{{$askList['askuid']}}" >
                    <img src="{{url("avatars/60/".$askList['avatar']."")}}" class="rich-avatar" data-card-url="/users/card/{{ $askList['askuid'] }}" />
                </a>
            </div>
            <div>
                <div>
                    <a href="{{url("questions/".$askList['askid']."")}}">
                        {{$askList['subject']}}
                    </a>
                </div>
                @if($askList['answered'] != 0)
                    <div class="">
                        <a href="{{url("profile/".$askList['answereduid']."")}}" class="rich-avatar" data-card-url="/users/card/{{ $askList['answereduid'] }}" data-text="{{$askList['answereduid']}}"  >{{$askList['username']}}</a><span>回复了问题</span>
                    </div>
                    <div class="figcaption">
                        <div>
                            {{$askList['detail']}}
                        </div>
                    </div>
                @else
                    <div class="display">
                        <a href="javascript:void(0)"></a><span></span>
                    </div>
                    <div class="figcaption">
                        <div>
                            {{$askList['askdetail']}}
                        </div>
                    </div>
                @endif
                <div>
                    @foreach($askList['tags'] as $tags)
                        <a href="{{url('questions/tagged/'.$tags->name)}}">{{$tags->name}}</a>
                    @endforeach
                </div>
                <div>
                    <span>{{$askList['askcreated_at']}}</span>
                    <span>赞( {{$askList['askvote_up']}} )</span>

                    <span>关注( {{$askList['stared']}} )</span>

                    @if($askList['answered']==0 )
                        <span>待回答</span>
                    @else
                        <span>回答( {{$askList['answered']}} )</span>

                    @endif
                    <span>浏览( {{$askList['viewed']}} )</span>

                </div>
            </div>
        </li>
    @endforeach
