@foreach ($templates as $template)
    <li>
        <a href="/cv/templates/{{ $template->id }}">
            <h3>{{ $template->subject }}</h3>
            <p>{{ $template->feature }}</p>
        </a>
    </li>
@endforeach