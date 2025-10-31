
document.addEventListener('DOMContentLoaded', function () {
    new EducationControl().init();
});

class EducationControl {
    init() {
        this.enableEducationCreation("#create-education", authRoute('user.education.save'));
        this.enableEducationEditing(".edit-education");
        this.enableEducationDeletion(".delete-education");
        this.enableEducationEditingBtns("#education-enable-editing");
        this.enableEducationDeleteBtns("#education-enable-deletion");
    }
    enableEducationCreation(selector, submitUrl) {
        const modal = document.querySelector("#education-form-modal");
        const modalTitle = modal.querySelector("#education-form-title");
        const baseForm = modal.querySelector('form');

        const createBtn = document.querySelector(selector);
        if (!createBtn) return;

        createBtn.addEventListener('click', function () {
            const oldForm = modal.querySelector('form');
            const newForm = baseForm.cloneNode(true);
            oldForm.replaceWith(newForm);

            modal.setAttribute('data-form-mode', 'create');
            modalTitle.textContent = "Add Education";
            newForm.reset();

            const saveBtn = newForm.querySelector('button[type="submit"]');

            newForm.addEventListener('submit', (e) => {
                e.preventDefault();
                saveBtn.disabled = true;
                saveBtn.textContent = "Saving...";

                const formData = new FormData(newForm);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(submitUrl, {
                    method: 'POST',
                    headers: { 'x-csrf-token': csrfToken },
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
                            newForm.reset();
                        } else {
                            Swal.fire("Oops!", data.message, "error");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire("Oops!", err.message, "error");
                    })
                    .finally(() => {
                        saveBtn.disabled = false;
                        saveBtn.textContent = "Save";
                    });
            });

            $(modal).modal('show');
        });
    }

    enableEducationEditing(selector) {
        const modal = document.querySelector("#education-form-modal");
        const modalTitle = modal.querySelector("#education-form-title");
        const editBtns = document.querySelectorAll(selector);
        if (!editBtns.length) return;

        editBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const oldForm = modal.querySelector('form');
                const newForm = oldForm.cloneNode(true);
                oldForm.replaceWith(newForm);

                modal.setAttribute('data-form-mode', 'edit');
                modalTitle.textContent = "Edit Education";

                const workExpId = btn.getAttribute('data-education-id');
                newForm.setAttribute("data-education-id", workExpId);

                setDataToForm(newForm, btn.dataset);

                const saveBtn = newForm.querySelector('button[type="submit"]');

                newForm.addEventListener('submit', (e) => {
                    e.preventDefault();

                    const submitUrl = authRoute('user.education.update', {
                        education: newForm.getAttribute('data-education-id')
                    });

                    saveBtn.disabled = true;
                    saveBtn.textContent = "Saving...";

                    const formData = new FormData(newForm);
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch(submitUrl, {
                        method: 'POST',
                        headers: { 'x-csrf-token': csrfToken },
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
                                newForm.reset();
                            } else {
                                Swal.fire("Oops!", data.message, "error");
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire("Oops!", err.message, "error");
                        })
                        .finally(() => {
                            saveBtn.disabled = false;
                            saveBtn.textContent = "Save";
                        });
                });

                $(modal).modal('show');
            });
        });
    }

    enableEducationDeletion(selector) {
        const btns = document.querySelectorAll(selector);
        if (!btns) {
            return;
        }

        btns.forEach(btn => {

            const colDiv = btn.closest('.col');
            btn.addEventListener('click', function () {
                const submitUrl = btn.getAttribute("data-delete-url");
                if (!submitUrl) {
                    return;
                }
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                colDiv.classList.add('deleting');
                fetch(submitUrl, {
                    method: 'DELETE',
                    headers: { 'x-csrf-token': csrfToken },
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === "success") {
                            colDiv.remove();
                            Swal.fire({
                                title: "Success",
                                text: data.message,
                                icon: "success"
                            }).then(() => window.location.reload());

                        } else {
                            Swal.fire("Oops!", data.message, "error");
                            colDiv.classList.remove('deleting');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire("Oops!", err.message, "error");
                        colDiv.classList.remove('deleting');
                    });
            });
        });

    }

    setDataToForm(form, data) {
        if (!form || !data) return;
        form.reset();

        form.querySelector('[name="institution"]').value = data.institution || '';
        form.querySelector('[name="degree"]').value = data.degree || '';
        form.querySelector('[name="field_of_study"]').value = data.fieldOfStudy || '';
        form.querySelector('[name="grade"]').value = data.grade || '';
        form.querySelector('[name="start_date"]').value = data.startDate || '';
        form.querySelector('[name="end_date"]').value = data.endDate || '';

        const desc = form.querySelector('[name="description"]');
        if (desc) {
            if (window.CKEDITOR && CKEDITOR.instances[desc.id]) {
                CKEDITOR.instances[desc.id].setData(data.description || '');
            } else {
                desc.value = data.description || '';
            }
        }

        const logoPreview = form.querySelector('#institution-logo-preivew');
        if (logoPreview) {
            logoPreview.innerHTML = '';
            if (data.institutionLogo) {
                const img = document.createElement('img');
                img.src = data.institutionLogo;
                img.alt = 'Institution Logo';
                img.classList.add('rounded', 'border', 'square-50');
                logoPreview.appendChild(img);
            }
        }
    }

    enableImagePick(input) {

        const modal = document.getElementById("education-form-modal");
        const imageBox = document.getElementById('institution-logo-preivew');

        const file = input.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {

                const img = document.createElement('img');
                img.classList.add('square-60');
                img.classList.add('border');
                img.classList.add('rounded');

                img.src = e.target.result;

                if (modal.getAttribute('data-form-mode') == "create") {
                    imageBox.innerHTML = '';
                    imageBox.appendChild(img);
                } else if (modal.getAttribute('data-form-mode') == "edit") {
                    const currentImage = imageBox.querySelector('.current-image');

                    if (currentImage) {
                        imageBox.innerHTML = currentImage.outerHTML;
                        imageBox.appendChild(img);
                    } else {
                        imageBox.innerHTML = '';
                        imageBox.appendChild(img);
                    }
                }

            };

            reader.readAsDataURL(file);
        } else {
            imageBox.innerHTML = '';
        }





    }

    enableEducationDeleteBtns(selector) {
        toggleClassOnView(selector, "deletion-enabled");
    }

    enableEducationEditingBtns(selector) {
        toggleClassOnView(selector, "editing-enabled");
    }

    toggleClassOnView(sourceSelector, className) {
        const input = document.querySelector(sourceSelector);
        const education = document.querySelector("#education_view");
        input.checked = false;
        input.addEventListener('change', () => {
            if (input.checked === true) {
                education.classList.add(className);
            } else {
                education.classList.remove(className);

            }
        });
    }
}



