<div class="ux-search-group">
    @forelse ($results as $result)
        {{-- <p class="ux-search-group-title">Suggestions</p> --}}

        <a href="{{ route('docs.show', [
            'user' => $result->user->username,
            'slug' => $result->documentation->url,
            'version' => $result->documentationRelease->version,
            'path' => $result->slug,
        ]) }}"
            class="ux-search-item">
            <div>
                <div class="ux-search-title mb-1">{{ $result->title }}</div>
                <div class="ux-search-meta">{{ \Illuminate\Support\Str::limit(strip_tags($result->content), 120) }}</div>
            </div>
        </a>
    @empty
        <p class="text-center text-muted fst-italic py-4">No results...</p>
    @endforelse
</div>
