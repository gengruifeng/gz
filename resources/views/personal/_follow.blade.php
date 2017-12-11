@if($info['info'])
    @foreach($info['info'] as $follow)
        <li class="list-normal">
            <div class="answer-div">
                <a href="javascript:void(0)">
                    <span>{{$follow->answered}}</span>
                    <span>回答</span>
                </a>
            </div>
            <div class="read-div">
                <a href="javascript:void(0)">
                    <span>{{$follow->viewed}}</span>
                    <span>阅读</span>
                </a>
            </div>
            <div class="content-div">
                <p class="answer-title"><a href="{{url('/questions/'.$follow->id)}}">
                        {{$follow->subject}}
                    </a></p>
                <p>
                    @if($follow->answered >0)
                        <a href="javascript:void(0)">{{$follow->answeredName}}等<span>{{$follow->answered}}人回答</span>
                        </a>
                    @else
                        <span>暂时还没有人回答</span>
                    @endif
                    <span>{{$follow->created_at}}</span>
                </p>
            </div>
        </li>
    @endforeach
@endif