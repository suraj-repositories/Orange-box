<div class="card">

    <div class="card-header">
        <div class="d-flex align-items-center">
            <div class="border border-dark rounded-2 me-2 widget-icons-sections">
                <i data-feather="bar-chart" class="widgets-icons"></i>
            </div>
            <h5 class="card-title mb-0">Documentation Visitors</h5>
        </div>
    </div>

    <div class="card-body">
        <div id="documentation-visitors" class="apex-charts" data-categories='@json($chart['categories'])'
            data-series='@json($chart['series'])'></div>
    </div>

</div>
