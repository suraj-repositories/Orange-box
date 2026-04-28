<div class="card">

    <div class="card-header">
        <div class="d-flex align-items-center">
            <div class="border border-dark rounded-2 me-2 widget-icons-sections">
                <i data-feather="minus-square" class="widgets-icons"></i>
            </div>
            <h5 class="card-title mb-0">Audiences By Time Of Day</h5>
        </div>
    </div>

    <div class="card-body">
        <div class="apex-charts mt-n3" id="audiences-daily" data-series='@json($heatmap)'></div>
    </div>

</div>
