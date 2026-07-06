<div class="btn-group">
    <button type="button" class="btn btn-sm border fw-semibold" id="copy-page-btn">
        Copy Page
    </button>

    <button type="button"
            class="btn btn-sm border dropdown-toggle dropdown-toggle-split d-flex align-items-center justify-content-center"
            data-bs-toggle="dropdown"
            aria-expanded="false">
       <i class="bi bi-chevron-down"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="width:300px">

        <li>
            <button class="dropdown-item py-2 active" type="button" id="view-markdown">
                <div class="d-flex">
                    <i class="bi bi-markdown fs-5 me-3 mt-1"></i>
                    <div>
                        <div class="fw-semibold">View as Markdown</div>
                        <small class="text-muted">
                            Open this page in Markdown
                        </small>
                    </div>
                </div>
            </button>
        </li>

        <li>
            <button class="dropdown-item py-2" type="button" id="open-chatgpt">
                <div class="d-flex">
                    <i class="bi bi-chat-dots fs-5 me-3 mt-1"></i>
                    <div>
                        <div class="fw-semibold">Open in ChatGPT</div>
                        <small class="text-muted">
                            Ask questions about this page
                        </small>
                    </div>
                </div>
            </button>
        </li>

        <li>
            <button class="dropdown-item py-2" type="button" id="open-claude">
                <div class="d-flex">
                    <i class="bi bi-stars fs-5 me-3 mt-1"></i>
                    <div>
                        <div class="fw-semibold">Open in Claude</div>
                        <small class="text-muted">
                            Ask questions about this page
                        </small>
                    </div>
                </div>
            </button>
        </li>


    </ul>
</div>
