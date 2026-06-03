<button class="btn border mt-2 gap-1 d-flex align-items-center" type="button" data-bs-toggle="modal"
    data-bs-target="#selectTemplateModal">

    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
        <path d="M0 0h24v24H0z" fill="none" />
        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5">
            <path stroke-linejoin="round"
                d="M14.98 7.016s.5.5 1 1.5c0 0 1.589-2.5 3-3M9.995 2.021c-2.499-.105-4.429.182-4.429.182c-1.219.088-3.554.77-3.554 4.762c0 3.956-.026 8.834 0 10.779c0 1.188.735 3.96 3.281 4.108c3.095.18 8.67.219 11.228 0c.684-.039 2.964-.576 3.252-3.056c.3-2.57.24-4.355.24-4.78" />
            <path
                d="M22 7.016c0 2.761-2.24 5-5.005 5a5 5 0 0 1-5.005-5c0-2.762 2.241-5 5.005-5a5 5 0 0 1 5.005 5Zm-15.02 6h4m-4 4h8" />
        </g>
    </svg>

    Select Another Template
</button>


<div class="modal" id="selectTemplateModal" tabindex="-1" aria-labelledby="selectTemplateModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="selectTemplateModalLabel">Select Template</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2">

                <ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="free-templates-tab" data-bs-toggle="tab"
                            data-bs-target="#free-templates" type="button" role="tab"
                            aria-controls="free-templates" aria-selected="true">Free</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="premium-templates-tab" data-bs-toggle="tab"
                            data-bs-target="#premium-templates-pane" type="button" role="tab"
                            aria-controls="premium-templates-pane" aria-selected="false">Preminum</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="my-templates-tab" data-bs-toggle="tab"
                            data-bs-target="#my-templates-pane" type="button" role="tab"
                            aria-controls="my-templates-pane" aria-selected="false">My Templates</button>
                    </li>

                </ul>
                <div class="tab-content modal-template-cards" id="myTabContent">
                    <div class="tab-pane fade show active" id="free-templates" role="tabpanel"
                        aria-labelledby="home-tab" tabindex="0">

                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-3 g-3"
                            id="freeTemplatesContainer">

                        </div>

                    </div>
                    <div class="tab-pane fade" id="premium-templates-pane">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-3 g-3"
                            id="premiumTemplatesContainer">
                        </div>
                    </div>


                    <div class="tab-pane fade" id="my-templates-pane">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-3 g-3"
                            id="myTemplatesContainer">
                        </div>


                    </div>

                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary " id="apply-template-button">Done</button>
            </div>
        </div>
    </div>
</div>
