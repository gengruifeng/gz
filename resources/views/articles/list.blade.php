@foreach ($articles['articles'] as $article)
    <li>
        <div>
            <a href="/articles/{{ $article->id }}">
                <img alt="{{ $article->subject }}" src="{{ $article->thumbnails }}"/>
            </a>
        </div>
        <div>
            <div>
                <p>
                    <a href="/articles/{{ $article->id }}">
                        {{ 32 < mb_strlen($article->subject) ? mb_substr($article->subject, 0, 32).'...' : $article->subject }}
                    </a>
                </p>
            </div>
            <div class="article-tag">
                @foreach ($article->tags as $tag)
                    <span>{{ $tag->name }}</span>
                @endforeach
            </div>
            <div>
                @if (isset($article->author))
                    <a href="/profile/{{ $article->author->id }}" class="rich-avatar" data-card-url="/users/card/{{ $article->author->id }}" data-text="{{ $article->author->id }}">{{ $article->author->display_name }}</a>
                @else
                    <a href="javascript:void(0)">佚名</a>
                @endif

                <span class="article-time">{{ $article->created_at }}</span>
                <span class="article-star">{{ $article->vote_up }}</span>
            </div>
        </div>
    </li>
@endforeach