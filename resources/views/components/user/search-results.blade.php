<div class="ux-app-search-group">
    @php $isEmpty = true; @endphp
    @foreach ($groups as $heading => $items)
        @if (empty($items))
            @continue
        @endif

        @php $isEmpty = false; @endphp

        <div class="ux-app-search-folder mb-1">

            @if ($heading !== 'General')
                <h6 class="ux-app-search-folder-title text-muted px-2 my-1">
                    {{ $heading }}
                </h6>
            @endif
            @foreach ($items as $item)
                <a href="{{ $item['link'] }}" class="ux-app-search-item px-2 py-2" data-url="{{ $item['link'] }}">

                    <div class="left-icon-box">
                        <i class="{{ $item['icon'] ?? 'bx bx-file' }}"></i>
                    </div>

                    <div>
                        <div class="ux-app-search-title fw-semibold">
                            {!! $item['heading'] ?? '' !!}
                        </div>

                        <div class="ux-app-search-meta small text-muted">
                            {!! $item['search_para'] ?? '' !!}
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
    @endforeach


    @if ($isEmpty)
        <div class="text-center text-muted fst-italic py-4">
            No results found
        </div>
    @endif



</div>
