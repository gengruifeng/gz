@foreach($comments as $comment)
<li>
    <div>
        <img alt="{{$comment->author->display_name}}" class="rich-avatar" data-card-url="/users/card/{{$comment->author->id}}" src="{{url("avatars/60/".$comment->author->avatar."")}}">
    </div>
    <div>
        <div>
            <a href="/profile/{{$comment->author->id}}" class="rich-avatar" data-card-url="/users/card/{{$comment->author->id}}" data-text="{{$comment->author->id}}">{{$comment->author->display_name}}</a>
            @if($comment->uid == $comment->author->id)
                <a href="/ajax/articles/{{$comment->article_id}}/comments/{{$comment->id}}/destroy" class="delete">删除</a>
                {{--<a href="javascript:void(0)" class="reply" data-mention-name="{{$comment->author->display_name}}">回复</a>--}}
            @else
            <a href="javascript:void(0)"></a>
            @endif
            <a href="javascript:void(0)"></a>
        </div>
        <div>
            @if($comment->author->occupation==1)
                <span>{{!empty($comment->author->education->school)?$comment->author->education->school:""}}</span>
                <span>{{!empty($comment->author->education->department)?$comment->author->education->department:""}}</span>
            @elseif($comment->author->occupation==2)
                <span>{{!empty($comment->author->work->company)?$comment->author->work->company:""}}</span>
                <span>{{!empty($comment->author->work->position)?$comment->author->work->position:""}}</span>
            @else
                <span></span>
                <span></span>
            @endif
            <span>{{$comment->updated_at}}</span>
        </div>
        <div>
            {{$comment->content}}
        </div>
    </div>
</li>
@endforeach