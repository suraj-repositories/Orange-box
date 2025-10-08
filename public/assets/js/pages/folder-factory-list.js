document.addEventListener('DOMContentLoaded', function () {
    enableFolderFactoryDelete(".delete-folder-button");
    enableFolderFactoryCreation("#create-folder-factory", authRoute('user.folder-factory.save'));
    enableFolderFactoryEdit(".edit-form-factory-btn");
});

function enableFolderFactoryDelete(selector) {
    const buttons = document.querySelectorAll(selector);
    if (!buttons) return;

    buttons.forEach(button => {
        button.addEventListener('click', function () {

            const folderFactoryId = button.getAttribute('data-folder-factory-id');

            if (!folderFactoryId) {
                return;
            }

            const folderCard = button.closest(".folder-card");
            if (folderCard) {
                folderCard.classList.add('deleting');
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(authRoute('user.folder-factory.delete', { folderFactory: folderFactoryId }), {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'x-csrf-token': csrfToken
                }
            })
                .then(response => response.json())
                .then(data => {
                    folderCard.classList.remove('deleting');
                    const isCol = folderCard.closest(".col");
                    if (isCol) {
                        isCol.remove();
                    } else {
                        folderCard.remove();
                    }

                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    folderCard.classList.remove('deleting');
                    folderCard.classList.add('delete-error');
                    setTimeout(() => {
                        folderCard.classList.remove('delete-error');
                    }, 3000);
                });

        });
    });

}

function enableFolderFactoryCreation(selector, submitUrl) {
    const btn = document.querySelector(selector);
    const modal = document.querySelector("#folder-factory-form-modal");

    if (!btn) return;

    btn.addEventListener('click', () => {
        const oldForm = modal.querySelector("#folder-factory-form");
        oldForm.replaceWith(oldForm.cloneNode(true));

        const title = modal.querySelector("#folder-factory-form-title");
        const saveBtn = modal.querySelector("#save-btn");
        if (!title || !saveBtn) {
            return;
        }

        title.textContent = "Create Folder"
        saveBtn.textContent = "Create";


        const form = modal.querySelector("#folder-factory-form");
        form.reset();
        const defaultIcon = form.querySelector('[name="icon"]');
        if(defaultIcon){
            defaultIcon.checked = true;
        }


        form.addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(form);

            saveBtn.disabled = true;
            saveBtn.textContent = "Creating...";

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(submitUrl, {
                method: 'POST',
                headers: {
                    'x-csrf-token': csrfToken
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.status == "success") {
                        $(modal).modal('hide');
                        Swal.fire({
                            title: "Success",
                            text: data.message,
                            icon: "success"
                        }).then(() => {
                            window.location.reload();
                        });;

                        form.reset();
                    } else {
                        Swal.fire({
                            title: "Oops!",
                            text: data.message,
                            icon: "error"
                        });
                    }
                    saveBtn.disabled = false;
                    saveBtn.textContent = "Create";
                })
                .catch(error => {
                    console.error('Error:', error);
                    saveBtn.disabled = false;
                    saveBtn.textContent = "Create";
                });
        });
        $(modal).modal('show');
    });
}

function enableFolderFactoryEdit(selector) {

    const btns = document.querySelectorAll(selector);
    const modal = document.querySelector("#folder-factory-form-modal");
    if (!btns || !modal) return;

    btns.forEach(btn => {
        const folderFactoryId = btn.getAttribute('data-ob-folder-factory-id');
        const submitUrl = authRoute('user.folder-factory.update', { folderFactory: folderFactoryId });
        btn.addEventListener('click', () => {
            const oldForm = modal.querySelector("#folder-factory-form");
            oldForm.replaceWith(oldForm.cloneNode(true));

            const title = modal.querySelector("#folder-factory-form-title");
            const saveBtn = modal.querySelector("#save-btn");
            if (!title || !saveBtn) {
                return;
            }

            title.textContent = "Edit Folder"
            saveBtn.textContent = "Save";

            const form = modal.querySelector("#folder-factory-form");

            if (!form) return console.error("Form not found inside modal.");

            const nameInput = form.querySelector("[name='name']");
            const folderIcons = form.querySelectorAll("[name='icon']");

            const folderName = btn.getAttribute('data-ob-folder-factory-name');
            const selectedIcon = btn.getAttribute('data-ob-folder-factory-icon');

            if (nameInput) {
                nameInput.value = folderName || "";
            }

            if (folderIcons.length) {
                if(selectedIcon){
                    folderIcons.forEach(icon => {
                        icon.checked = (icon.value === selectedIcon);
                    });
                }else{
                    const defaultIcon = form.querySelector('[name="icon"]');
                    if(defaultIcon){
                        defaultIcon.checked = true;
                    }
                }
            }
            form.addEventListener('submit', (event) => {
                event.preventDefault();

                const formData = new FormData(form);

                saveBtn.disabled = true;
                saveBtn.textContent = "Saving...";

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(submitUrl, {
                    method: 'POST',
                    headers: {
                        'x-csrf-token': csrfToken
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {

                        if (data.status == "success") {
                            $(modal).modal('hide');
                            Swal.fire({
                                title: "Success",
                                text: data.message,
                                icon: "success"
                            }).then(() => {
                                window.location.reload();
                            });;

                            form.reset();
                        } else {
                            Swal.fire({
                                title: "Oops!",
                                text: data.message,
                                icon: "error"
                            });
                        }
                        saveBtn.disabled = false;
                        saveBtn.textContent = "Save";
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: "Oops!",
                            text: error,
                            icon: "error"
                        });
                        saveBtn.disabled = false;
                        saveBtn.textContent = "Save";
                    });
            });

            $(modal).modal('show');
        });
    });

}
