@foreach ($templates as $template)
    <li>
        <dl class="clearfix">
            <dt class="fl"><a href="/cv/templates/{{ $template->id }}"><img width="190" src="/templates/preview/{{ $template->preview }}" alt=""></a></dt>
            <dd class="fl">
                <h3><a href="/cv/templates/{{ $template->id }}">{{ $template->subject }}</a></h3>
                <p >简历模板特点: </p>
                <p class="tips">{{ $template->feature }}</p>
                <p class="numbs">简历模板下载量：{{ $template->downloaded }}</p>
            </dd>
        </dl>
    </li>
@endforeach