@php
    function highlight($text, $query)
    {
        return preg_replace("/($query)/i", '<mark>$1</mark>', normalizeText($text));
    }

    function normalizeText($content)
    {
        $text = strip_tags($content);

        // Step 2: Remove Markdown syntax
        $text = preg_replace('/(```.*?```)/s', ' ', $text);
        $text = preg_replace('/`.*?`/', ' ', $text);
        $text = preg_replace('/!\[.*?\]\(.*?\)/', ' ', $text);
        $text = preg_replace('/\[(.*?)\]\(.*?\)/', '$1', $text);
        $text = preg_replace('/[#>*_\-~]/', ' ', $text);

        // Normalize spaces
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        return $text;
    }

    function searchSnippet($content, $query, $limit = 80)
    {
        $text = normalizeText($content);

        $length = strlen($text);

        $lowerText = strtolower($text);
        $lowerQuery = strtolower($query);

        $pos = strpos($lowerText, $lowerQuery);

        if ($pos !== false) {
            $start = max($pos - $limit / 2, 0);
            $snippet = substr($text, $start, $limit);

            // Clean broken words BEFORE adding dots
            $snippet = preg_replace('/^\S*\s/', '', $snippet);
            $snippet = preg_replace('/\s\S*$/', '', $snippet);

            if ($start > 0) {
                $snippet = '...' . $snippet;
            }

            if ($start + $limit < $length) {
                $snippet .= '...';
            }
        } else {
            $snippet = substr($text, 0, $limit);

            $snippet = preg_replace('/\s\S*$/', '', $snippet);

            if ($limit < $length) {
                $snippet .= '...';
            }
        }

        $snippet = preg_replace('/' . preg_quote($query, '/') . '/i', '<mark>$0</mark>', $snippet);

        return $snippet;
    }
@endphp

<div class="ux-search-group">

    @forelse ($groups as $folder => $items)
        <div class="ux-search-folder mb-1">

            @if ($folder !== 'General')
                <h6 class="ux-search-folder-title text-muted px-2 my-1">
                    {{ $folder }}
                </h6>
            @endif
            @foreach ($items as $section)
                @php
                    $url =
                        route('docs.show', [
                            'user' => $section->page->user->username,
                            'slug' => $section->page->documentation->url,
                            'version' => $section->page->documentationRelease->version,
                            'path' => $section->page->full_path,
                        ]) .
                        '#' .
                        $section->slug;
                @endphp

                <a href="{{ $url }}" class="ux-search-item px-2 py-2" data-url="{{ $url }}">

                    <div class="left-icon-box">
                        <i class="bx bx-file"></i>
                    </div>

                    <div>
                        <div class="ux-search-title fw-semibold">
                            {!! highlight($section->heading, $query) !!}
                        </div>

                        <div class="ux-search-meta small text-muted">
                            {!! searchSnippet($section->content, $query) !!}
                        </div>

                        {{-- <div class="small text-muted">
                            in {{ $section->page->title }}
                        </div> --}}
                    </div>

                    <div class="right-icon-box">
                        <i class='bx bx-subdirectory-right'></i>
                    </div>
                </a>
            @endforeach

        </div>
    @empty
        <div class="text-center text-muted fst-italic py-4">
            No results found
        </div>
    @endforelse

</div>
