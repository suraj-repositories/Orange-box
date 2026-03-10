@php
    $pages = [
        'terms' => [
            'title' => 'Terms',
            'route' => route('docs.extras.show', [
                'user' => $user->username,
                'slug' => $documentation->url,
                'version' => $release->version,
                'type' => 'terms',
            ]),
        ],
        'guide' => [
            'title' => 'Community Guide',
            'route' => route('docs.extras.show', [
                'user' => $user->username,
                'slug' => $documentation->url,
                'version' => $release->version,
                'type' => 'guide',
            ]),
        ],
        'code_of_conduct' => [
            'title' => 'Code of conduct',
            'route' => route('docs.extras.show', [
                'user' => $user->username,
                'slug' => $documentation->url,
                'version' => $release->version,
                'type' => 'code_of_conduct',
            ]),
        ],
        'privacy' => [
            'title' => 'Privacy Policy',
            'route' => route('docs.extras.show', [
                'user' => $user->username,
                'slug' => $documentation->url,
                'version' => $release->version,
                'type' => 'privacy',
            ]),
        ],
    ];
@endphp

@foreach ($pages as $type => $page)
    @php
        $doc = $documentationDocuments->get($type);
    @endphp

    @if ($doc && $doc->status === 'active')
        <a class="dropdown-item notify-item" href="{{ $page['route'] }}">
            <span>{{ $page['title'] }}</span>
        </a>
    @endif
@endforeach
