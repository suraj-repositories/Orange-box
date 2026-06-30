document.addEventListener('DOMContentLoaded', function () {
    enableHotPagesStatusChange();
    enableRefreshButton("#refreshPagesButton");

});

function enableHotPagesStatusChange() {

    const switches = document.querySelectorAll('.hot-pages-card .change-status-checkbox');

    if (switches.length <= 0) {
        console.warn("No switches there");
        return;
    }

    const updateDocumentStatus = (documentId, status, checkbox) => {

        if (!documentId || !status) {
            console.error("Invalid argument passed!");
            return;
        }

        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content');

        fetch(authRoute('user.documentation.document.status.update', { document: documentId }), {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'x-csrf-token': csrfToken
            },
            body: JSON.stringify({ status: status })
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {

                    Toastify.success(data.message);

                    const listItem = checkbox.closest('.document-list-item');
                    const badge = listItem.querySelector('.page-status-badge');

                    if (!badge) return;

                    if (status === 'active') {
                        badge.textContent = 'Active';
                        badge.classList.remove('bg-danger-subtle', 'text-danger');
                        badge.classList.add('bg-success-subtle', 'text-success');
                    } else {
                        badge.textContent = 'Inactive';
                        badge.classList.remove('bg-success-subtle', 'text-success');
                        badge.classList.add('bg-danger-subtle', 'text-danger');
                    }

                } else {

                    Toastify.error(data.message);
                    checkbox.checked = !checkbox.checked;

                }

            })
            .catch(error => {

                console.error('Error:', error);
                Toastify.error("Something went wrong!");
                checkbox.checked = !checkbox.checked;

            });
    }

    switches.forEach(checkbox => {

        checkbox.addEventListener('change', function () {

            const documentId = checkbox.getAttribute('data-document-id');
            const status = checkbox.checked ? 'active' : 'inactive';

            updateDocumentStatus(documentId, status, checkbox);

        });

    });

}



function enableRefreshButton(selector) {

    const button = document.querySelector(selector);
    if (!button) return;

    const reloadActionArea = document.querySelector("#reloadActionArea");
    const progressBox = document.querySelector("#reloadActionArea .load-progress");
    const progressText = document.querySelector("#syncCount");
    const modal = document.getElementById("refreshPagesModal");

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    let polling = null;

    function stopPolling() {
        if (polling) {
            clearInterval(polling);
            polling = null;
        }
    }

    function updateProgress(data) {

        if (!data.running) {
            progressBox.classList.add("d-none");
            reloadActionArea.classList.remove('loading-doc');
            button.disabled = false;
            stopPolling();
            return;
        }

        progressBox.classList.remove("d-none");
        reloadActionArea.classList.add('loading-doc');

        progressText.textContent =
            `${data.processed} / ${data.total} (${data.progress}%)`;

        button.disabled = true;

        if (data.finished) {

            progressText.textContent =
                `${data.total} / ${data.total} (100%)`;

            button.disabled = false;

            stopPolling();

            setTimeout(() => {
                progressBox.classList.add("d-none");
                reloadActionArea.classList.remove('loading-doc');
            }, 2000);
        }
    }

    function checkProgress() {

        fetch(button.dataset.progressUrl)
            .then(response => response.json())
            .then(updateProgress)
            .catch(console.error);

    }

    function startPolling() {

        stopPolling();

        checkProgress();

        polling = setInterval(checkProgress, 2000);

    }

    button.addEventListener("click", function () {

        button.disabled = true;

        fetch(button.dataset.submitUrl, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            }
        })
            .then(response => response.json())
            .then(data => {

                if (!data.success) {
                    button.disabled = false;
                    Toastify.error(data.message);
                    return;
                }

                bootstrap.Modal.getInstance(modal)?.hide();

                startPolling();

                Toastify.info("Page refresh started.");

            })
            .catch(error => {

                console.error(error);

                button.disabled = false;

                Toastify.error("Something went wrong.");

            });

    });

    startPolling();
}
