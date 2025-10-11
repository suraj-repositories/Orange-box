document.addEventListener('DOMContentLoaded', function () {

    enableModuleTaskCreation("#create-module-task");
    enableModuleTaskEdit(".edit-module-task-btn");
});

function enableModuleTaskCreation(selector) {
    const btn = document.querySelector(selector);
    const modal = document.querySelector("#module-task-form-modal");

    if (!btn) return;

    btn.addEventListener('click', () => {


        const oldForm = modal.querySelector("#module-task-form");
        oldForm.replaceWith(oldForm.cloneNode(true));

        const title = modal.querySelector("#module-task-form-title");
        const saveBtn = modal.querySelector("#save-btn");
        if (!title || !saveBtn) {
            return;
        }

        title.textContent = "Create Task"
        saveBtn.textContent = "Create";

        const form = modal.querySelector("#module-task-form");
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

            const projectBoardId = btn.getAttribute('project-board-id');
            const projectModuleId = formData.get('project_module');
            const submitUrl = authRoute('user.project-board.modules.task.store', {projectBoard: projectBoardId, projectModule: projectModuleId});
            console.log(submitUrl);
        //     const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        //     fetch(submitUrl, {
        //         method: 'POST',
        //         headers: {
        //             'x-csrf-token': csrfToken
        //         },
        //         body: formData
        //     })
        //         .then(response => response.json())
        //         .then(data => {
        //             console.log(data);
        //             if (data.status == "success") {
        //                 $(modal).modal('hide');
        //                 Swal.fire({
        //                     title: "Success",
        //                     text: data.message,
        //                     icon: "success"
        //                 }).then(() => {
        //                     window.location.reload();
        //                 });;

        //                 form.reset();
        //             } else {
        //                 Swal.fire({
        //                     title: "Oops!",
        //                     text: data.message,
        //                     icon: "error"
        //                 });
        //             }
        //             saveBtn.disabled = false;
        //             saveBtn.textContent = "Create";
        //         })
        //         .catch(error => {
        //             console.error('Error:', error);
        //             saveBtn.disabled = false;
        //             saveBtn.textContent = "Create";
        //         });
        });
        $(modal).modal('show');
    });
}

function enableModuleTaskEdit(selector) {

    const btns = document.querySelectorAll(selector);
    const modal = document.querySelector("#module-task-form-modal");
    if (!btns || !modal) return;

    btns.forEach(btn => {
        const ModuleTaskId = btn.getAttribute('data-ob-module-task-id');
        const submitUrl = authRoute('project-board.modules.task.update', { task: ModuleTaskId });
        btn.addEventListener('click', () => {
            const oldForm = modal.querySelector("#module-task-form");
            oldForm.replaceWith(oldForm.cloneNode(true));

            const title = modal.querySelector("#module-task-form-title");
            const saveBtn = modal.querySelector("#save-btn");
            if (!title || !saveBtn) {
                return;
            }

            title.textContent = "Edit Task"
            saveBtn.textContent = "Save";

            const form = modal.querySelector("#module-task-form");

            if (!form) return console.error("Form not found inside modal.");

            const nameInput = form.querySelector("[name='name']");
            const folderIcons = form.querySelectorAll("[name='icon']");

            const folderName = btn.getAttribute('data-ob-module-task-name');
            const selectedIcon = btn.getAttribute('data-ob-module-task-icon');

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
            // form.addEventListener('submit', (event) => {
            //     event.preventDefault();

            //     const formData = new FormData(form);

            //     saveBtn.disabled = true;
            //     saveBtn.textContent = "Saving...";

            //     const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            //     fetch(submitUrl, {
            //         method: 'POST',
            //         headers: {
            //             'x-csrf-token': csrfToken
            //         },
            //         body: formData
            //     })
            //         .then(response => response.json())
            //         .then(data => {

            //             if (data.status == "success") {
            //                 $(modal).modal('hide');
            //                 Swal.fire({
            //                     title: "Success",
            //                     text: data.message,
            //                     icon: "success"
            //                 }).then(() => {
            //                     window.location.reload();
            //                 });;

            //                 form.reset();
            //             } else {
            //                 Swal.fire({
            //                     title: "Oops!",
            //                     text: data.message,
            //                     icon: "error"
            //                 });
            //             }
            //             saveBtn.disabled = false;
            //             saveBtn.textContent = "Save";
            //         })
            //         .catch(error => {
            //             console.error('Error:', error);
            //             Swal.fire({
            //                 title: "Oops!",
            //                 text: error,
            //                 icon: "error"
            //             });
            //             saveBtn.disabled = false;
            //             saveBtn.textContent = "Save";
            //         });
            // });

            $(modal).modal('show');
        });
    });

}
