
document.addEventListener('DOMContentLoaded', function () {
    enablePasswordToggle();
    enablePasswordLockerCreation("#create-password-locker", authRoute('user.password_locker.save'));
    enableLockerInfoView();
    enablePasswordLockerEdit(".edit-password-locker-btn");
    enablePasswordLockerDelete(".delete-password-locker-button");
    enablePasswordShow(".reveal-password-btn");
});
function enablePasswordToggle() {
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('#toggle-password');
        if (!btn) return;
        const passInput = document.getElementById('password-input');
        const icon = btn.querySelector('i');
        if (passInput.type === "password") {
            passInput.type = "text";
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            passInput.type = "password";
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });
}

function enableLockerInfoView() {
    $(document).on('click', '.show-locker-info', function () {
        $('#created_at')
            .text($(this).data('ob-created-at') || '-')
            .attr('title', $(this).data('ob-created-at-title') || '-');

        $('#updated_at')
            .text($(this).data('ob-updated-at') || '-')
            .attr('title', $(this).data('ob-updated-at-title') || '-');

        $('#notes-content').html($(this).data('ob-notes-content') || '<p class="fs-5 text-muted p-4 text-center fst-italic"> NO DATA </p>');

        $('#password-locker-info-modal').modal('show');
    });
}

function enablePasswordLockerDelete(selector) {
    const buttons = document.querySelectorAll(selector);
    if (!buttons) return;

    buttons.forEach(button => {
        button.addEventListener('click', function () {

            const deleteUrl = button.getAttribute('data-ob-password-locker-delete-url');

            if (!deleteUrl) {
                return;
            }

            const deletableRow = button.closest("tr");
            if (deletableRow) {
                deletableRow.classList.add('deleting');
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'x-csrf-token': csrfToken
                }
            })
                .then(response => response.json())
                .then(data => {
                    deletableRow.classList.remove('deleting');
                    const isCol = deletableRow.closest(".col");
                    if (isCol) {
                        isCol.remove();
                    } else {
                        deletableRow.remove();
                    }

                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    deletableRow.classList.remove('deleting');
                    deletableRow.classList.add('delete-error');
                    setTimeout(() => {
                        deletableRow.classList.remove('delete-error');
                    }, 3000);
                });

        });
    });

}

function enablePasswordLockerCreation(selector, submitUrl) {
    const btn = document.querySelector(selector);
    const modal = document.querySelector("#password-locker-form-modal");

    if (!btn) return;

    btn.addEventListener('click', () => {

        const oldForm = modal.querySelector("#password-locker-form");
        oldForm.replaceWith(oldForm.cloneNode(true));

        const title = modal.querySelector("#password-locker-form-title");
        const saveBtn = modal.querySelector("#save-btn");
        if (!title || !saveBtn) {
            return;
        }

        title.textContent = "Create Password"
        saveBtn.textContent = "Create";


        const form = modal.querySelector("#password-locker-form");
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

function enablePasswordLockerEdit(selector) {
    const btns = document.querySelectorAll(selector);
    const modal = document.querySelector("#password-locker-form-modal");
    if (!btns || !modal) return;

    btns.forEach(btn => {
        const submitUrl = btn.getAttribute('data-ob-submit-url');

        btn.addEventListener('click', () => {
            const form = modal.querySelector("#password-locker-form");
            const title = modal.querySelector("#password-locker-form-title");
            const saveBtn = modal.querySelector("#save-btn");

            if (!form || !title || !saveBtn) return;

            form.reset();

            title.textContent = "Edit Password";
            saveBtn.textContent = "Save";

            const usernameInput = form.querySelector("[name='username']");
            const passwordInput = form.querySelector("[name='password']");
            const urlInput = form.querySelector("[name='url']");
            const expiresAtInput = form.querySelector("[name='expires_at']");
            const notesInput = form.querySelector("[name='notes']");

            usernameInput.value = btn.getAttribute('data-ob-username') || "";
            passwordInput.value = btn.getAttribute('data-ob-password') || "";
            urlInput.value = btn.getAttribute('data-ob-url') || "";
            expiresAtInput.value = btn.getAttribute('data-ob-expires-at') || "";

            const notes = btn.getAttribute('data-ob-notes') || "";
            notesInput.value = notes;
            notesInput.dataset.markdown = notes;

            const newForm = form.cloneNode(true);
            form.replaceWith(newForm);

            newForm.addEventListener('submit', async (event) => {
                event.preventDefault();

                const saveBtn = modal.querySelector("#save-btn");
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const notesField = newForm.querySelector("[name='notes']");
                if (notesField && notesField.editorInstance) {
                    notesField.value = notesField.editorInstance.getData();
                }

                const formData = new FormData(newForm);

                saveBtn.disabled = true;
                saveBtn.textContent = "Saving...";

                fetch(submitUrl, {
                    method: "POST",
                    headers: { "x-csrf-token": csrfToken },
                    body: formData
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === "success") {
                            $(modal).modal('hide');
                            Swal.fire({
                                title: "Success",
                                text: data.message,
                                icon: "success"
                            }).then(() => window.location.reload());
                        } else {
                            Swal.fire({
                                title: "Oops!",
                                text: data.message,
                                icon: "error"
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        Swal.fire({
                            title: "Oops!",
                            text: "Something went wrong.",
                            icon: "error"
                        });
                    })
                    .finally(() => {
                        saveBtn.disabled = false;
                        saveBtn.textContent = "Save";
                    });
            });

            $(modal).modal("show");
        });
    });
}


function enablePasswordShow(selector) {
    const btns = document.querySelectorAll(selector);
    if (!btns) {
        return;
    }

    const modal = document.querySelector("#password-locker-show-modal");

    btns.forEach(btn => {
        const submitUrl = btn.getAttribute('data-ob-show-password-url');

        btn.addEventListener('click', () => {
            $(modal).modal('show');
            // fetch(submitUrl)
            //     .then(r => r.json())
            //     .then(data => {
            //         if (data.status === "success") {


            //         } else {

            //         }
            //     })
            //     .catch(error => {
            //         console.error("Error:", error);
            //         Swal.fire({
            //             title: "Oops!",
            //             text: "Something went wrong.",
            //             icon: "error"
            //         });
            //     });

        });
    });

}
