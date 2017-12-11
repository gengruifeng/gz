@if($info['info'])
    @foreach($info['info'] as $question)
        <li class="list-normal">
            <div class="answer-div">
                <a href="javascript:void(0)">
                    <span>{{$question->answered}}</span>
                    <span>回答</span>
                </a>
            </div>
            <div class="read-div">
                <a href="javascript:void(0)">
                    <span>{{$question->viewed}}</span>
                    <span>阅读</span>
                </a>
            </div>
            <div class="content-div">
                <p>
                    <a href="{{url('/questions/'.$question->id)}}">{{$question->subject}}</a>
                </p>
                <p>
                    @if($question->answered >0)
                        @foreach($question->answeredName as $answeredName)
                            <a href="javascript:void(0)">{{$answeredName}}</a>&nbsp;
                        @endforeach
                        <span>参与回答</span>
                    @else
                        <span>暂时还没有人回答</span>
                    @endif
                    <span>{{$question->created_at}}</span>
                </p>
            </div>
        </li>
    @endforeach
@endif