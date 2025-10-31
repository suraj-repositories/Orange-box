
document.addEventListener('DOMContentLoaded', () => {
    new WorkExperienceControl().init();
});

class WorkExperienceControl {
    init() {
        this.enableWorkExperienceCreation("#create-work-experience", authRoute('user.work_experience.save'));
        this.enableWorkExperienceEditing(".edit-work-experience");
        this.enableWorkExperienceDeletion(".delete-work-experience");
        this.enableEditingBtns("#work-experience-enable-editing");
        this.enableDeleteBtns("#work-experience-enable-deletion");
    }

    enableWorkExperienceCreation(selector, formSubmitUrl) {

        const modal = document.querySelector("#work-experience-form-modal");
        const modalTitle = modal.querySelector("#work-experience-form-title");
        const baseForm = modal.querySelector('form');
        const createBtn = document.querySelector(selector);
        if (!createBtn) return;

        createBtn.addEventListener('click', function () {
            const oldForm = modal.querySelector('form');
            const newForm = baseForm.cloneNode(true);
            oldForm.replaceWith(newForm);

            modal.setAttribute('data-form-mode', 'create');
            modalTitle.textContent = "Add Work Experience";
            newForm.reset();

            const saveBtn = newForm.querySelector('button[type="submit"]');

            newForm.addEventListener('submit', (e) => {
                e.preventDefault();
                saveBtn.disabled = true;
                saveBtn.textContent = "Saving...";

                const formData = new FormData(newForm);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(formSubmitUrl, {
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

    enableWorkExperienceEditing(selector) {
        const modal = document.querySelector("#work-experience-form-modal");
        const modalTitle = modal.querySelector("#work-experience-form-title");
        const editBtns = document.querySelectorAll(selector);
        if (!editBtns.length) return;

        editBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const oldForm = modal.querySelector('form');
                const newForm = oldForm.cloneNode(true);
                oldForm.replaceWith(newForm);

                modal.setAttribute('data-form-mode', 'edit');
                modalTitle.textContent = "Edit Work Experience";

                const workExpId = btn.getAttribute('data-work-experience-id');
                newForm.setAttribute("data-work-experience-id", workExpId);

                setDataToForm(newForm, btn.dataset);

                const saveBtn = newForm.querySelector('button[type="submit"]');

                newForm.addEventListener('submit', (e) => {
                    e.preventDefault();

                    const formSubmitUrl = authRoute('user.work_experience.update', {
                        workExperience: newForm.getAttribute('data-work-experience-id')
                    });

                    saveBtn.disabled = true;
                    saveBtn.textContent = "Saving...";

                    const formData = new FormData(newForm);
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch(formSubmitUrl, {
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

    enableWorkExperienceDeletion(selector) {
        const btns = document.querySelectorAll(selector);
        if (!btns) {
            return;
        }

        btns.forEach(btn => {

            const colDiv = btn.closest('.col');
            btn.addEventListener('click', function () {
                const formSubmitUrl = btn.getAttribute("data-delete-url");
                if (!formSubmitUrl) {
                    return;
                }
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                colDiv.classList.add('deleting');
                fetch(formSubmitUrl, {
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

        form.querySelector('[name="job_title"]').value = data.jobTitle || '';
        form.querySelector('[name="employment_type"]').value = data.employmentType || '';
        form.querySelector('[name="company"]').value = data.company || '';
        form.querySelector('[name="location"]').value = data.location || '';
        form.querySelector('[name="start_date"]').value = data.startDate || '';
        form.querySelector('[name="end_date"]').value = data.endDate || '';

        const chk = form.querySelector('[name="currently_working"]');
        if (chk) chk.checked = data.currentlyWorking === 'yes' || data.currentlyWorking === true;

        const desc = form.querySelector('[name="description"]');
        if (desc) {
            if (window.CKEDITOR && CKEDITOR.instances[desc.id]) {
                CKEDITOR.instances[desc.id].setData(data.description || '');
            } else {
                desc.value = data.description || '';
            }
        }

        const logoPreview = form.querySelector('#company-logo-preivew');
        if (logoPreview) {
            logoPreview.innerHTML = '';
            if (data.companyLogo) {
                const img = document.createElement('img');
                img.src = data.companyLogo;
                img.alt = 'Company Logo';
                img.classList.add('rounded', 'border', 'square-50');
                logoPreview.appendChild(img);
            }
        }
    }

    enableImagePick(input) {

        const modal = document.getElementById("work-experience-form-modal");
        const imageBox = document.getElementById('company-logo-preivew');

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

    enableCurrentlyWorkingCheckbox() {
        const endDateInput = document.querySelector('input[name="end_date"]');
        const currentlyWorkingCheck = document.querySelector('#currently-working-input');

        if (currentlyWorkingCheck.checked) {
            endDateInput.value = "";
            endDateInput.disabled = true;
        } else {
            endDateInput.disabled = false;
        }

    }

    enableDeleteBtns(selector) {
        toggleClassOnView(selector, "deletion-enabled");
    }

    enableEditingBtns(selector) {
        toggleClassOnView(selector, "editing-enabled");
    }

    toggleClassOnView(sourceSelector, className) {
        const input = document.querySelector(sourceSelector);
        const workExperienceView = document.querySelector("#work_experience_view");
        input.checked = false;
        input.addEventListener('change', () => {
            if (input.checked === true) {
                workExperienceView.classList.add(className);
            } else {
                workExperienceView.classList.remove(className);

            }
        });
    }

}
