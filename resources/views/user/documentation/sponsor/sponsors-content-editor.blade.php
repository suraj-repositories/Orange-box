<form action="{{ authRoute('user.documentation.sponsors.content.save', ['documentation' => $documentation]) }}"
    id="saveSponsorDocumentForm" method="POST">
    @csrf
    <div class="card">
        <div class="card-header p-2 d-flex align-items-center gap-2 justify-content-between flex-wrap">

            <div class="d-flex align-items-center">
                <div class="rounded-2 me-2 widget-icons-sections">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26px" height="26px" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M6.08 10.103h2.914L9.657 12h1.417L8.23 4H6.846L4 12h1.417zm1.463-4.137l.994 2.857h-2zM11 16H4v-1.5h7zm1 0h8v-1.5h-8zm-4 4H4v-1.5h4zm7-1.5V20H9v-1.5z" />
                    </svg>
                </div>

                <h5 class="mb-0 card-title ">Sponsors Page Content</h5>
            </div>

            <input type="hidden" name="submit_status" value="publish">
            <div class="d-flex align-items-center gap-1">
                <input type="checkbox" class="btn-check" id="previewToggleCheckbox" autocomplete="off">
                <label class="btn btn-outline-dark" for="previewToggleCheckbox"><i
                        class="bi bi-file-earmark-richtext me-1"></i> Preview</label><br>

                <button type="submit" class="btn btn-primary"><i class="bi bi-send-check me-1"></i> Publish</button>

            </div>
        </div>

        <div class="card-body">
            <div id="editorjs-editor" data-ob-submit-form="#saveSponsorDocumentForm"
                data-ob-image-upload-url="{{ authRoute('user.documentation.document.editor.images.store') }}"
                data-ob-preview-toggle-checkbox="#previewToggleCheckbox" data-ob-cacheable-id="syntax-store-editorjs"
                @if (!empty($sponsorDocument)) data-ob-content='{{ $sponsorDocument->content }}' @endif
                data-ob-fetch-online-media-url="{{ authRoute('user.documentation.document.editor.fetch-url-media') }}"
                data-ob-fetch-data-url="{{ authRoute('user.documentation.document.editor.fetch-url-data') }}">
            </div>
            <pre id="output"></pre>
        </div>
    </div>
</form>
