document.addEventListener('DOMContentLoaded', function(){
    enableModuleCreation("#create-project-module");
});
function enableModuleCreation(selector){
    const btn = document.querySelector(selector);
    const modal = document.querySelector("#project-module-form-modal");

    if (!btn) return;

    btn.addEventListener('click', () => {
        const oldForm = modal.querySelector("#project-module-form");
        oldForm.replaceWith(oldForm.cloneNode(true));

        const title = modal.querySelector("#project-module-form-title");
        const saveBtn = modal.querySelector("#save-btn");
        if (!title || !saveBtn) {
            return;
        }

        title.textContent = "Create Module"
        saveBtn.textContent = "Create";


        const form = modal.querySelector("#project-module-form");
        form.reset();
        const defaultIcon = form.querySelector('[name="icon"]');
        if(defaultIcon){
            defaultIcon.checked = true;
        }


        // form.addEventListener('submit', function (event) {
        //     event.preventDefault();
        //     const formData = new FormData(form);

        //     saveBtn.disabled = true;
        //     saveBtn.textContent = "Creating...";

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
        // });
        $(modal).modal('show');
    });
}
