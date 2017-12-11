@if($info['info'])
    @foreach($info['info'] as $article)
        <li class="list-normal">
            <div class="answer-div">
                <a href="javascript:void(0)">
                    <span>{{$article->stared}}</span>
                    <span>收藏</span>
                </a>
            </div>
            <div class="read-div">
                <a href="javascript:void(0)">
                    <span>{{$article->viewed}}</span>
                    <span>阅读</span>
                </a>
            </div>

            <div class="content-div">
                <p class="answer-title"><a href="{{url('/articles/'.$article->id)}}">{{$article->subject}}</a></p>
                <p>
                    <a href="javascript:void(0)">{{$article->display_name}}</a>
                    @if($article->describe)
                        <span>{{$article->describe['first'].",".$article->describe['second']}}</span>
                    @else
                        <span></span>
                    @endif
                    <span>{{$article->created_at}}</span>
                </p>
            </div>
        </li>
    @endforeach
@endif