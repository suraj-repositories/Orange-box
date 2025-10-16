document.addEventListener('DOMContentLoaded', function () {
    enableSubTaskCreation("#new-sub-task-btn");
    enableSubTaksEditing(".edit-sub-task-btn");
});


function enableSubTaskCreation(selector) {
    const btn = document.querySelector(selector);
    const modal = document.querySelector("#sub_task_form_modal");
    btn.addEventListener('click', function () {
        const submitUrl = btn.getAttribute('data-submit-url');
        const title = modal.querySelector('#sub_task_form_title');
        title.textContent = "Create Sub Task";

        const form = document.querySelector('#sub_task_form_modal form');
        const prevMode = form.getAttribute('data-form-mode');
        form.action = submitUrl;

        if (prevMode != 'create') {

            const listView = modal.querySelector("#list-view-container");
            listView.innerHTML = "";

            const descriptionBox = form.querySelector('textarea');
            descriptionBox.value = "";
            descriptionBox.setAttribute('data-markdown', "");

            form.setAttribute('data-form-mode', 'create');

            const radios = form.querySelectorAll('[name="status"]');
            if (radios) {
                radios.forEach(radio => {
                    if (radio.value != "completed") {
                        radio.checked = false;
                    }
                });
            }
        }

        $(modal).modal('show');
    });
}


function enableSubTaksEditing(selector) {
    const editBtns = document.querySelectorAll(selector);

    if (!editBtns) {
        return;
    }

    const modal = document.querySelector("#sub_task_form_modal");
    const form = document.querySelector('#sub_task_form_modal form');
    const title = modal.querySelector('#sub_task_form_title');
    const listView = modal.querySelector("#list-view-container");
    const descriptionBox = form.querySelector('textarea');
    const radios = form.querySelectorAll('[name="status"]');

    editBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const submitUrl = btn.getAttribute('data-submit-url');
            form.action = submitUrl;
            form.setAttribute('data-form-mode', 'edit');

            title.textContent = "Edit Sub Task";

            listView.innerHTML = createFilesListHtml(btn.getAttribute("data-subtask-files"));

            const markdown = btn.getAttribute('data-subtask-description');
            descriptionBox.value = markdown;
            descriptionBox.setAttribute('data-markdown', markdown);

            if (radios) {
                radios.forEach(radio => {
                    radio.checked = false;
                    if (radio.value == btn.getAttribute('data-subtask-status')) {
                        radio.checked = true;
                    }
                });
            }
            $(modal).modal('show');

        });
    });
}

function createFilesListHtml(data) {
    let html = "";
    const parsedData = JSON.parse(data);

    parsedData.forEach(item => {
        html += createFileListItem(item);
    });

    return html;
}

function createFileListItem(data) {
    return `<div class="card border-0 rounded-0 list-row-card" data-media-files-index="0">
                        <div class="horizontal-viewer">
                            <div class="list-title">
                                <div class="icon"><i class="bi ${data.extension_icon}"></i></div>
                                <div class="name text-truncate">${data.file_name}</div>
                            </div>
                            <div class="list-data">
                                <div class="type">${data.extension}</div>
                                <div class="size">${data.formatted_file_size}</div>
                                <div class="action">
                                    <a class="show" href="${data.file_url}" target="_blank" data-ob-view="view-file"><i class="bx bx-show-alt"></i></a>
                                    <a class="delete" href="javascript:void(0)" data-subtask-file-id="${data.id}" onclick="deleteMeListCard(this)" data-og-dismiss="list-item-card" data-bs-toggle="tooltip" data-bs-title="Delete"><i class="bx bx-trash-alt"></i></a>
                                </div>
                            </div>
        </div>
    </div>`;
}

function deleteMeListCard(deleteBtn) {
    const card = deleteBtn.closest('.list-row-card');
    const fileId = deleteBtn.getAttribute('data-subtask-file-id');
    const url = route('file.delete', { file: fileId });

    card.classList.add('deleting');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(url, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
        .then(response => {
            if (!response.ok) throw new Error("Failed request");
            return response.json();
        })
        .then(data => {
            card.remove();
        })
        .catch(error => {
            console.error('Error:', error);
            card.classList.remove('deleting');
        });

}
