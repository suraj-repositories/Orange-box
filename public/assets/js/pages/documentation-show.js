document.addEventListener('DOMContentLoaded', function () {
    enableHotPagesStatusChange();
    enableRefreshButton("#refreshPagesButton");
    enableLoadEntireDocsForm("#refreshEntireDocsForm");

    initializeSyncPolling();
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

function createSyncProgress() {

    const reloadActionArea = document.querySelector("#reloadActionArea");
    const progressBox = reloadActionArea.querySelector(".load-progress");

    const progressText = progressBox.querySelector("#syncCount");
    const progressCircle = progressBox.querySelector(".progress-bar");
    const progressPercent = progressBox.querySelector("#syncPercent");

    const radius = 42;
    const circumference = 2 * Math.PI * radius;

    progressCircle.style.strokeDasharray = circumference;
    progressCircle.style.strokeDashoffset = circumference;

    function set(percent, processed, total) {

        percent = Math.max(0, Math.min(100, Number(percent)));

        const offset =
            circumference - (percent / 100) * circumference;

        progressCircle.style.strokeDashoffset = offset;
        progressPercent.textContent = `${percent}%`;
        progressText.textContent = `${processed} / ${total}`;

    }

    function show() {
        progressBox.classList.remove("d-none");
        reloadActionArea.classList.add("loading-doc");
    }

    function hide() {
        progressBox.classList.add("d-none");
        reloadActionArea.classList.remove("loading-doc");
    }

    return {
        show,
        hide,
        set
    };

}

const SyncPolling = (() => {

    let timer = null;
    let progressUrl = null;

    const listeners = [];

    function subscribe(callback) {

        listeners.push(callback);

    }

    function notify(data) {

        listeners.forEach(cb => cb(data));

    }

    function poll() {

        fetch(progressUrl)
            .then(r => r.json())
            .then(data => {

                notify(data);

                if (!data.running) {
                    console.log(data);
                    Toastify.success(data.message);
                    stop();
                }

            })
            .catch(console.error);

    }

    function start(url) {

        progressUrl = url;

        if (timer) {
            return;
        }

        poll();

        timer = setInterval(poll, 2000);

    }

    function stop() {

        if (!timer) {
            return;
        }

        clearInterval(timer);

        timer = null;

    }

    return {
        start,
        stop,
        subscribe
    };

})();


function createProgressHandler(options) {

    const progress = createSyncProgress();

    return function (data) {

        if (!data.running) {

            progress.hide();
            options.enable();

            return;
        }

        progress.show();

        progress.set(
            data.progress,
            data.processed,
            data.total
        );

        options.disable();

        if (data.finished) {

            progress.set(100, data.total, data.total);

            options.enable();

            setTimeout(progress.hide, 2000);

        }

    };

}

function postJson(url, body = null) {

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .content;

    return fetch(url, {

        method: "POST",

        headers: {
            "X-CSRF-TOKEN": csrfToken,
            "Accept": "application/json"
        },

        body

    }).then(r => r.json());

}

function enableRefreshButton(selector) {

    const button = document.querySelector(selector);

    if (!button) return;

    const modal = document.getElementById("refreshPagesModal");

    const originalHtml = button.innerHTML;

    const handler = createProgressHandler({

        disable() {
            button.disabled = true;
        },

        enable() {
            button.disabled = false;
            button.innerHTML = originalHtml;
        }

    });

    SyncPolling.subscribe(handler);

    button.addEventListener("click", () => {

        button.disabled = true;

        button.innerHTML =
            `<div class="shaft-loader loader-light"></div> Loading...`;

        postJson(button.dataset.submitUrl)

            .then(data => {

                if (!data.success) {

                    button.disabled = false;
                    button.innerHTML = originalHtml;

                    Toastify.error(data.message);

                    return;

                }

                bootstrap.Modal.getInstance(modal)?.hide();

                SyncPolling.start(button.dataset.progressUrl);

                Toastify.info("Page refresh started.");

            })

            .catch(console.error);

    });

}

function enableLoadEntireDocsForm(selector) {

    const form = document.querySelector(selector);

    if (!form) return;

    const submitButton =
        form.querySelector('button[type="submit"]');

    const modal =
        document.getElementById("loadDocsModal");

    const originalHtml = submitButton.innerHTML;

    const handler = createProgressHandler({

        disable() {
            submitButton.disabled = true;
        },

        enable() {
            submitButton.disabled = false;
            submitButton.innerHTML = originalHtml;
        }

    });

    SyncPolling.subscribe(handler);

    form.addEventListener("submit", e => {

        e.preventDefault();

        submitButton.disabled = true;

        submitButton.innerHTML =
            `<div class="shaft-loader loader-light"></div> Loading...`;

        postJson(
            form.action,
            new FormData(form)
        )

            .then(data => {

                if (!data.success) {

                    submitButton.disabled = false;

                    submitButton.innerHTML = originalHtml;

                    Toastify.error(data.message);

                    return;

                }

                bootstrap.Modal.getInstance(modal)?.hide();

                SyncPolling.start(form.dataset.progressUrl);

                Toastify.info("Documentation import started.");

            })

            .catch(console.error);

    });

}


function initializeSyncPolling() {

    const reloadActionArea = document.querySelector("#reloadActionArea");

    if (!reloadActionArea) {
        return;
    }

    if (reloadActionArea.dataset.loadInProgress !== "true") {
        return;
    }

    const progressUrl = reloadActionArea.dataset.progressUrl;

    if (!progressUrl) {
        return;
    }

    SyncPolling.start(progressUrl);

}
