<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-3 g-3 dashboard-template-cards">
    @foreach ($templates as $template)
        <div class="col">
            <div class="template-card d-flex flex-column h-100">
                <a href="{{ authRoute('user.template.show', ['template' => $template]) }}">
                    <img class="card-img-top rounded-top" src="{{ $template->preview_image_url }}" alt="Project Thumbnail" data-url="{{ $template->preview_image_url }}"
                        onerror="this.onerror=null;this.src='{{ asset('assets/images/defaults/placeholder-600x400.svg') }}';">
                </a>
                <div class="template-meta">
                    <div>
                        <div class="user-title">
                            <img class="proflie-image" src="{{ theme_asset('logo-sm.svg') }}" alt="">
                            <h2 class="mb-0"><a class="text-dark"
                                    href="{{ authRoute('user.template.show', ['template' => $template]) }}">{{ $template->title }}</a>
                            </h2>
                        </div>

                        <div class="rating text-muted"><i class="bi bi-star-fill"></i>
                            Rating : {{ round($template->reviews_avg_rating, 1) }}
                            | {{ $template->reviews_count ?? 0 }} Reviews

                        </div>
                    </div>
                    <div class="price-badge">
                        @if (($template->price ?? 0) > 0)
                            $ {{ $template->price }}
                        @else
                            Free
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach


</div>
