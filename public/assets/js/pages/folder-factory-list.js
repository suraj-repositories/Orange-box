document.addEventListener('DOMContentLoaded', function () {
    enableFolderFactoryDelete(".delete-folder-button");
    enableFolderFactoryCreation("#create-folder-factory", authRoute('user.folder-factory.save'));
    enableFolderFactoryEdit(".edit-form-factory-btn");
    enableIsFavouriteToggle(".favourite_toggle");
    enableCheckboxes('.select-checkbox');
    enableSelectedFileDetails("#openDetailsButton");
    enableFileInfoButton(".file-info-button");
    enableFileReallocation();
    enableFileRename();
    enableOpenFolderButton("#OpenSelectedFolderBtn");
    enableDeleteFile(".delete-file-button");
    enableDeleteAllSelected("#deleteAllSelectedBtn");
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
        if (defaultIcon) {
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
                if (selectedIcon) {
                    folderIcons.forEach(icon => {
                        icon.checked = (icon.value === selectedIcon);
                    });
                } else {
                    const defaultIcon = form.querySelector('[name="icon"]');
                    if (defaultIcon) {
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

function enableIsFavouriteToggle(selector) {
    const toggleBtns = document.querySelectorAll(selector);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!toggleBtns.length) return;

    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function () {

            const itemType = btn.dataset.itemType;
            const itemId = btn.dataset.itemId;

            const relatedBtns = document.querySelectorAll(
                `[data-item-type="${itemType}"][data-item-id="${itemId}"]`
            );

            if ([...relatedBtns].some(b => b.dataset.loading === '1')) {
                return;
            }

            const oldFavourite = btn.dataset.isFavourite === '1';
            const newFavourite = !oldFavourite;

            relatedBtns.forEach(b => {
                b.dataset.loading = '1';
                b.dataset.isFavourite = newFavourite ? '1' : '0';

                const icon = b.querySelector('i');
                const text = b.querySelector('.text');
                if (!icon) return;

                icon.classList.remove('bx-star', 'bxs-star');
                icon.classList.add(newFavourite ? 'bxs-star' : 'bx-star');

                if (text) {
                    text.textContent = newFavourite ? 'Make Unfavourite' : 'Make Favourite';
                }
            });

            fetch(authRoute('user.folder-factory.files.favourite.toggle'), {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    item_type: itemType,
                    item_id: itemId,
                    is_favourite: newFavourite
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Update failed');
                    }

                    relatedBtns.forEach(b => {
                        b.dataset.isFavourite = data.is_favourite ? '1' : '0';
                    });
                })
                .catch(error => {

                    relatedBtns.forEach(b => {
                        b.dataset.isFavourite = oldFavourite ? '1' : '0';

                        const icon = b.querySelector('i');
                        const text = b.querySelector('.text');
                        if (!icon) return;

                        icon.classList.remove('bx-star', 'bxs-star');
                        icon.classList.add(oldFavourite ? 'bxs-star' : 'bx-star');

                        if (text) {
                            text.textContent = newFavourite ? 'Make Unfavourite' : 'Make Favourite';
                        }
                    });

                    console.error(error);
                })
                .finally(() => {
                    relatedBtns.forEach(b => {
                        delete b.dataset.loading;
                    });
                });
        });
    });
}

function enableCheckboxes(selector) {
    const checks = document.querySelectorAll(selector);

    const totalItems = document.querySelector('.total-items');
    const itemsSelected = document.querySelector('.items-selected');
    const selectedText = itemsSelected?.querySelector('strong');
    const clearBtn = itemsSelected?.querySelector('.square-30');

    if (!checks.length || !totalItems || !itemsSelected || !selectedText) {
        return;
    }

    function updateSelectionState() {
        let checkCount = 0;

        checks.forEach(check => {
            if (check.checked) {
                checkCount++;
            }
        });

        if (checkCount > 0) {
            totalItems.classList.add('d-none');
            itemsSelected.classList.remove('d-none');
            selectedText.textContent = `${checkCount} Item${checkCount > 1 ? 's' : ''} Selected`;
        } else {
            itemsSelected.classList.add('d-none');
            totalItems.classList.remove('d-none');
        }
    }

    checks.forEach(check => {
        check.addEventListener('change', updateSelectionState);
    });

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            checks.forEach(check => {
                check.checked = false;
            });

            updateSelectionState();
        });
    }

    updateSelectionState();
}

function populateFileDetailsOffcanvas(item, offcanvasElement) {

    const singleSection = offcanvasElement.querySelector('.details-single');

    const isFile = item.dataset.type === 'file';

    offcanvasElement.querySelectorAll('.for-file').forEach(el => {
        el.classList.toggle('d-none', !isFile);
    });

    offcanvasElement.querySelectorAll('.for-folder').forEach(el => {
        el.classList.toggle('d-none', isFile);
    });

    const image = offcanvasElement.querySelector('.detail-image');
    const icon = offcanvasElement.querySelector('.detail-icon');

    image.classList.add('d-none');
    icon.classList.remove('d-none');

    if (
        isFile &&
        item.dataset.mimeType &&
        item.dataset.mimeType.startsWith('image/') &&
        item.dataset.fileUrl
    ) {
        image.src = item.dataset.fileUrl;
        image.classList.remove('d-none');
        icon.classList.add('d-none');
    } else {
        icon.className = 'detail-icon display-3';

        if (isFile) {
            icon.classList.add('bx', 'bx-file');
        } else {
            icon.classList.add('bx', 'bx-folder');
        }
    }

    offcanvasElement.querySelector('.detail-name').textContent =
        item.dataset.name || '-';

    offcanvasElement.querySelector('.detail-type').textContent =
        isFile ? 'File' : 'Folder';

    offcanvasElement.querySelector('.detail-mime-type').textContent =
        item.dataset.mimeType || '-';

    offcanvasElement.querySelector('.detail-size').textContent =
        item.dataset.size || '-';

    offcanvasElement.querySelector('.detail-modified').textContent =
        item.dataset.modified || '-';

    offcanvasElement.querySelector('.detail-created').textContent =
        item.dataset.created || '-';

    offcanvasElement.querySelector('.detail-item-count').textContent =
        item.dataset.itemCount || '0';

    singleSection.classList.remove('d-none');
}

function enableSelectedFileDetails(selector) {
    const btn = document.querySelector(selector);

    if (!btn) return;

    const offcanvasElement = document.getElementById('fileDetailsOffcanvas');
    const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement);

    const noneSection = offcanvasElement.querySelector('.details-none');
    const multipleSection = offcanvasElement.querySelector('.details-multiple');
    const singleSection = offcanvasElement.querySelector('.details-single');
    const selectedCount = offcanvasElement.querySelector('.selected-count');

    btn.addEventListener('click', function () {

        const selected = [...document.querySelectorAll('.select-checkbox:checked')];

        noneSection.classList.add('d-none');
        multipleSection.classList.add('d-none');
        singleSection.classList.add('d-none');

        if (selected.length === 0) {

            noneSection.classList.remove('d-none');

        } else if (selected.length > 1) {

            selectedCount.textContent = selected.length;
            multipleSection.classList.remove('d-none');

        } else {

            populateFileDetailsOffcanvas(selected[0], offcanvasElement);
        }

        offcanvas.show();
    });
}

function enableFileInfoButton(selector) {

    const btns = document.querySelectorAll(selector);

    if (!btns.length) return;

    const offcanvasElement = document.getElementById('fileDetailsOffcanvas');
    const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement);

    const noneSection = offcanvasElement.querySelector('.details-none');
    const multipleSection = offcanvasElement.querySelector('.details-multiple');
    const singleSection = offcanvasElement.querySelector('.details-single');

    btns.forEach(btn => {

        btn.addEventListener('click', function () {

            noneSection.classList.add('d-none');
            multipleSection.classList.add('d-none');
            singleSection.classList.add('d-none');

            populateFileDetailsOffcanvas(btn, offcanvasElement);

            offcanvas.show();
        });

    });
}

function enableFileRename() {
    {
        const renameModal = document.getElementById('file-rename-modal');
        const form = document.getElementById('file-rename-form');

        if (!form) { return; }
        const nameInput = form.querySelector('input[name="name"]');



        let fileId = null;

        document.querySelectorAll('[data-ob-file-id]').forEach(btn => {
            btn.addEventListener('click', function () {
                fileId = this.dataset.obFileId;

                nameInput.value = this.dataset.obFileName || '';
                renameModal.querySelector('.file-name-input').value = nameInput.value;

                const modal = bootstrap.Modal.getOrCreateInstance(renameModal);
                modal.show();
            });
        });

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            if (!fileId) return;

            try {
                const response = await fetch(route('file.rename.ajax', { file: fileId }), {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: nameInput.value
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Rename failed');
                }

                bootstrap.Modal.getInstance(renameModal)?.hide();

                location.reload();

            } catch (error) {

                Trastify.error(error.message);
            }
        });
    }
}

function enableFileReallocation(title) {
    const modal = document.querySelector('#file-realocation-modal');

    if (!modal) return;

    const modalTitle = modal.querySelector('.modal-title');
    const modalSubmitBtn = modal.querySelector('.submit-btn');
    const form = modal.querySelector('#file-realocation-form');
    const btns = document.querySelectorAll('.file-reallocation');

    if (btns.length <= 0) return;

    btns.forEach(btn => {
        btn.addEventListener('click', function () {
            const title = this.dataset.actionTitle;

            modalTitle.textContent = title;
            modalSubmitBtn.textContent = title;
            form.action = btn.dataset.submitUrl;

            $(modal).modal('show');
        });
    });
}

function enableOpenFolderButton(selector) {
    const btn = document.querySelector(selector);
    const parent = btn.parentElement;
    const select = parent.querySelector('select');
    const redirectLink = parent.querySelector('#redirect-link');

    if (select) {
        btn.addEventListener('click', () => {
            const selectedOption = select.options[select.selectedIndex];

            if (selectedOption) {
                const slug = selectedOption.dataset.slug;

                if (slug) {
                    redirectLink.href = authRoute('user.folder-factory.files.index', { slug: slug });
                    redirectLink.target = "_blank";
                    redirectLink.click();
                } else {
                    console.warn('No slug found for selected option');
                }
            } else {
                console.warn('No option selected');
            }
        });
    }

}

function enableDeleteFile(selector) {
    const btns = document.querySelectorAll(selector);
    const trashTabButton = document.querySelector('#trashTabButton');

    if (!btns.length) return;

    if (!trashTabButton) {
        console.error('trash tab not available on view!');
        return;
    }

    btns.forEach(btn => {
        btn.addEventListener('click', async function () {


            const folders = [];
            const files = [];

            const isPermanentDelete = trashTabButton.classList.contains('active');

            if (btn.dataset.type == 'file') {
                files.push(btn.dataset.id);
            } else if (btn.dataset.type == 'folder') {
                folders.push(btn.dataset.id);
            }

            const itemType = btn.dataset.type === 'folder' ? 'folder' : 'file';

            const confirmed = await Swal.fire({
                title: isPermanentDelete
                    ? `Permanently delete this ${itemType}?`
                    : `Delete this ${itemType}?`,
                text: isPermanentDelete
                    ? `This ${itemType} will be permanently deleted and cannot be recovered.`
                    : `This ${itemType} will be moved to the trash.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: isPermanentDelete
                    ? 'Permanent Delete'
                    : 'Delete',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-dark'
                },
                reverseButtons: true
            });

            if (!confirmed.isConfirmed) {
                return;
            }

            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute('content');

            btn.disabled = true;

            try {
                const response = await fetch(
                    !isPermanentDelete ?
                        authRoute('user.folder-factory.delete.all') : authRoute('user.folder-factory.delete.all.permanent'),
                    {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            file_ids: files,
                            folder_ids: folders
                        })
                    }
                );

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(
                        data.message || 'Failed to delete selected items.'
                    );
                }

                Toastify.success(
                    data.message || 'Selected items deleted successfully.'
                );

                setTimeout(() => {
                    window.location.reload();
                }, 500);

            } catch (error) {
                console.error(error);

                Toastify.error(
                    error.message || 'Something went wrong. Please try again.'
                );
            } finally {
                btn.disabled = false;
            }
        });
    });
}

function enableDeleteAllSelected(selector) {
    const btn = document.querySelector(selector);
    const trashTabButton = document.querySelector('#trashTabButton');

    if (!btn) return;

    if (!trashTabButton) {
        console.error('trash tab not available on view!');
    }


    btn.addEventListener('click', async function () {
        const checks = document.querySelectorAll('.select-checkbox:checked');

        if (!checks.length) {
            Toastify.warning('Please select at least one file or folder.');
            return;
        }

        const folders = [];
        const files = [];

        const isPermanentDelete = trashTabButton.classList.contains('active');


        checks.forEach(check => {
            const { type, id } = check.dataset;

            if (type === 'file') {
                files.push(id);
            } else if (type === 'folder') {
                folders.push(id);
            }
        });

        const confirmed = await Swal.fire({
            title: isPermanentDelete ? 'Permanent delete selected items?' : 'Delete selected items?',
            text: `You are about to delete ${files.length + folders.length} item(s). This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: isPermanentDelete ? 'Permanent Delete' : 'Delete',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-dark'
            },
            reverseButtons: true
        });

        if (!confirmed.isConfirmed) {
            return;
        }

        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content');

        btn.disabled = true;

        try {
            const response = await fetch(
                !isPermanentDelete ?
                    authRoute('user.folder-factory.delete.all') : authRoute('user.folder-factory.delete.all.permanent'),
                {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        file_ids: files,
                        folder_ids: folders
                    })
                }
            );

            const data = await response.json();

            if (!response.ok) {
                throw new Error(
                    data.message || 'Failed to delete selected items.'
                );
            }

            Toastify.success(
                data.message || 'Selected items deleted successfully.'
            );

            setTimeout(() => {
                window.location.reload();
            }, 500);

        } catch (error) {
            console.error(error);

            Toastify.error(
                error.message || 'Something went wrong. Please try again.'
            );
        } finally {
            btn.disabled = false;
        }
    });
}
