@if($info['info'])
    @foreach($info['info'] as $collect)
        <li class="list-normal">
            <div class="answer-div">
                <a href="javascript:void(0)">
                    <span>{{$collect->stared}}</span>
                    <span>收藏</span>
                </a>
            </div>
            <div class="read-div">
                <a href="javascript:void(0)">
                    <span>{{$collect->viewed}}</span>
                    <span>阅读</span>
                </a>
            </div>
            <div class="content-div">
                <p class="answer-title"><a href="{{url('/articles/'.$collect->id)}}">{{$collect->subject}}</a></p>
                <p>
                    <a href="javascript:void(0)">{{$collect->display_name}}</a>
                    @if($collect->describe)
                        <span>{{$collect->describe['first'].",".$collect->describe['second']}}</span>
                    @else
                        <span></span>
                    @endif
                    <span>{{$collect->created_at}}</span>
                </p>
            </div>
        </li>
    @endforeach
@endif