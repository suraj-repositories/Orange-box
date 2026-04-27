  <div class="row g-0 sidebar-sponsors">

      <div class="col-12 sponsor-column">
          <a href="{{ route('docs.extras.show', [
              'user' => request('user')['username'],
              'slug' => $documentation->url,
              'version' => request('version') ?? 'all',
              'type' => $document->type,
          ]) }}"
              class="border border-gray-100 sponsors-m-2 p-3 card-height text-center d-flex align-items-center justify-content-center">
              <span>Inquire about special sponsorship</span>

          </a>
      </div>

      @foreach ($sponsors as $sponsor)
          @php
              $logoLight = $sponsor->logo_light;
              $logoDark = $sponsor->logo_dark;
              $hasLogo = !empty($logoLight) || !empty($logoDark);
          @endphp

          <div class="col-6 col-md-6 sponsor-column">
              <div
                  class="border border-gray-100 sponsors-m-2 p-3 card-height text-center d-flex align-items-center justify-content-center">

                  @if (!empty($sponsor->website_url))
                      <a href="{{ $sponsor->website_url }}" target="_blank" rel="noopener"
                          class="w-100 text-decoration-none">
                  @endif

                  @if ($hasLogo)
                      @if ($logoLight)
                          <img src="{{ Storage::url($logoLight) }}" alt="{{ $sponsor->name }}"
                              class="img-fluid sponsor-logo logo-light">
                      @endif

                      @if ($logoDark)
                          <img src="{{ Storage::url($logoDark) }}" alt="{{ $sponsor->name }}"
                              class="img-fluid sponsor-logo logo-dark">
                      @endif
                  @else
                      <span class="fw-semibold">{{ $sponsor->name }}</span>
                  @endif

                  @if (!empty($sponsor->website_url))
                      </a>
                  @endif

              </div>
          </div>
      @endforeach
  </div>
