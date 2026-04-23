@foreach ($documentationDocuments as $document)
    @if ($document && $document->status === 'live')
        <a class="dropdown-item notify-item"
            href="{{ route('docs.extras.show', [
                'user' => $user->username,
                'documentationSlug' => $documentation->url,
                'version' => $release->version ?? 'all',
                'type' => $document->type,
            ]) }}">
            <span>{{ $document->title }}</span>
        </a>
    @endif
@endforeach

@foreach ($customDocumentationDocuments as $uuid => $document)
    @if ($document && $document->status === 'live')
        <a class="dropdown-item notify-item"
            href="{{ route('docs.extras.show', [
                'user' => $user->username,
                'documentationSlug' => $documentation->url,
                'version' => $release->version ?? 'all',
                'type' => $document->type,
                'u' => $uuid,
            ]) }}">
            <span>{{ $document->title }}</span>
        </a>
    @endif
@endforeach
