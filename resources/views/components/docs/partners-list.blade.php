<div class="row g-3">
    @foreach ($partners as $partner)
        <div class="col-12 col-sm-6">
            <div class="card border h-100">
                <div class="card-body p-3 pb-0">

                    <h2 class="mb-2">{{ $partner->name }}</h2>

                    <p class="location-text d-flex align-items-center text-muted mb-3">
                        <i class='bx bx-map me-1'></i>
                        {{ $partner->location }}
                    </p>

                    <p>
                        {{ $partner->short_description }}
                    </p>

                </div>
                <div class="card-footer pt-0">
                    <img src="{{ Storage::url($partner->banner) }}" class="img-fluid rounded" alt="">
                </div>
            </div>
        </div>
    @endforeach
</div>
