@foreach($articles as $article)
    <li class="">
        <div>
            <p><a style="color: #f87e6a;" href="{{asset('articles/'.$article->id)}}">{{$article->subject}}</a></p>
            <a href="javascript:void(0)">{{$article->updated_at}}</a>
        </div>
        <div>
            @if($article->standard == 0)
                <a class="offPass" href="javascript:void(0)">小编还在审核，耐心等待哦～</a>
            @elseif($article->standard == 1)
                <a class="" href="javascript:void(0)">已发布</a>
            @elseif($article->standard == 2 || $article->standard == 3)
                <a class="offPass" href="javascript:void(0)">未通过审核</a>
            @endif
        </div>
    </li>
@endforeach