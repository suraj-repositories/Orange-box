@if (!empty($document))
    <a href="{{ route('docs.extras.show', [
        'user' => $user,
        'slug' => $documentation->url,
        'version' => $release->version ?? 'all',
        'type' => $document->type,
    ]) }}"
        class="nav-link in-full-nav dropdown-toggle nav-user me-0 {{ Route::is('docs.extras.show') && request('type') == 'sponsors' ? 'active' : '' }}">
        <span class="pro-user-name ms-1">Sponsor</span>
    </a>
@endif
