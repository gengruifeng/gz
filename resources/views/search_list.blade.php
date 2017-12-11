@foreach ($questions as $question)
    <li class="clearfix">
        <div>
            <a href="/profile/{{ $question->askuser[0]->id }}" class="rich-avatar" data-card-url="/users/card/{{ $question->askuser[0]->id }}"  data-text="{{ $question->askuser[0]->id }}" >
                <img src="/avatars/60/{{ $question->askuser[0]->avatar }}" />
            </a>
        </div>
        <div>
            <div>
                <a href="/questions/{{ $question->id }}">
                    {{ $question->subject }}
                </a>
            </div>

            @if (isset($question->newAnswer))
                <div class="">
                    <a href="/profile/{{ $question->newAnswer->id }}" class="rich-avatar" data-card-url="/users/card/{{ $question->newAnswer->id }}"  data-text="{{ $question->newAnswer->id }}"  >{{ $question->newAnswer->display_name }}</a><span>回复了问题</span>
                </div>
            @else
                <div id="noThingButpos"></div>

            @endif

            <div class="figcaption">
                <div>
                    {{ strip_tags($question->detail) }}
                </div>
            </div>
            <div>
                @foreach ($question->tags as $tag)
                    <a href="/questions/tagged/{{ $tag->name }}">{{ $tag->name }}</a>
                @endforeach
            </div>
            <div>
                <span>{{ $question->created_at }}</span>
                <span>赞( {{ $question->vote_up }} )</span>
                <span>关注( {{ $question->stared }} )</span>
                @if (0 < $question->answered)
                    <span>回答( {{ $question->answered }} )</span>

                @else
                    <span>待回答</span>
                @endif
                <span>浏览( {{ $question->viewed }} )</span>

            </div>
        </div>
    </li>
@endforeach