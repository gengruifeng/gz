@if($info['info'])
    @foreach($info['info'] as $answer)
        <li class="list-special clearfix">
            <div class="answer-div">
                <a href="javascript:void(0)">
                    <span>{{ $answer->vote_up }}</span>
                    <span>赞</span>
                </a>
            </div>
           <div class="content-special fl">
               <div class="answer-title">
                   <a href="{{url('/questions/'.$answer->questionId)}}">{{$answer->title}}</a>
               </div>
               <div class="answer-other">
                   {!!$answer->detail!!}
               </div>
               <div class="answer-time">回答时间 : <span>{{$answer->created_at}}</span></div>
           </div>
        </li>
    @endforeach
@endif
