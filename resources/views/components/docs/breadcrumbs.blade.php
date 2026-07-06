<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a
                href="{{ route('docs.show', [
                    'user' => $username,
                    'slug' => $documentation->url,
                    'version' => $version,
                    'path' => '',
                ]) }}">{{ $documentation->title }}</a>
        </li>

        @foreach ($pathItems as $item)
            <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}"
                @if ($loop->last) aria-current="page" @endif>
                {{ $item }}
            </li>
        @endforeach
    </ol>
</nav>
