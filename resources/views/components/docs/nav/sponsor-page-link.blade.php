@if (!empty($document))
    <a href="{{ route('docs.extras.show', [
        'user' => $user,
        'documentationSlug' => $documentation->url,
        'version' => $release->version ?? 'all',
        'slug' => $document->slug,
    ]) }}"
        class="nav-link in-full-nav dropdown-toggle nav-user me-0 {{-- Route::is('docs.sponsors.index') ? 'active' : '' --}}">
        <span class="pro-user-name ms-1">Sponsor</span>
    </a>
@endif
