document.addEventListener('DOMContentLoaded', function () {
    enableSponsorCreation('#create-sponsor-button');
    enableSponsorEditing(".edit-sponsor");
    initFileChoosers();
    enableStatusUpdate('.sponsorStatusToggleSwitch');
});

let fileChoosers = {};

function initFileChoosers() {

    document.querySelectorAll(".doc-logo-picker label").forEach(label => {

        const input = label.querySelector("input[type='file']");
        if (!input) return;

        const pickerBox = document.createElement("div");
        pickerBox.classList.add("picker-box");

        input.insertAdjacentElement("afterend", pickerBox);

        function renderPreview(src) {
            pickerBox.innerHTML = "";

            const img = document.createElement("img");
            img.src = src;

            const removeBtn = document.createElement("button");
            removeBtn.type = "button";
            removeBtn.classList.add("remove-btn");
            removeBtn.innerHTML = "×";

            removeBtn.onclick = function (e) {
                e.preventDefault();
                input.value = "";
                pickerBox.innerHTML = "<span>Click to upload</span>";
            };

            pickerBox.appendChild(img);
            pickerBox.appendChild(removeBtn);
        }

        function resetPreview() {
            pickerBox.innerHTML = "<span>Click to upload</span>";
            input.value = "";
        }

        pickerBox.innerHTML = "<span>Click to upload</span>";

        input.addEventListener("change", function () {
            if (!this.files || !this.files[0]) return;

            const reader = new FileReader();
            reader.onload = e => renderPreview(e.target.result);
            reader.readAsDataURL(this.files[0]);
        });

        fileChoosers[input.name] = {
            renderPreview,
            resetPreview
        };
    });

}

function openSponsorModal() {

    const modalEl = document.getElementById('sponsor-form-modal');

    document.getElementById('sponsor-id').value = '';
    document.getElementById('sponsor-name-input').value = '';

    document.querySelector("[name='website_url']").value = "";
    document.querySelector("[name='tier']").value = "";

    if (fileChoosers.logo_light) fileChoosers.logo_light.resetPreview();
    if (fileChoosers.logo_dark) fileChoosers.logo_dark.resetPreview();

    const editorEl = document.querySelector('#description-editor');
    editorEl.value = '';
    editorEl.dataset.markdown = '';

    if (editorEl.editorInstance) {
        editorEl.editorInstance.setData('');
    }

    document.getElementById('sponsor-creation-form-title').innerText = "Create Sponsor";

    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}

function enableSponsorCreation(selector) {
    const button = document.querySelector(selector);
    if (!button) return;
    button.addEventListener('click', openSponsorModal);
}

function enableSponsorEditing(selector) {

    const btns = document.querySelectorAll(selector);
    const modal = document.querySelector("#sponsor-form-modal");

    if (!btns || !modal) return;

    btns.forEach(btn => {

        btn.addEventListener("click", () => {

            const form = modal.querySelector("#sponsor-creation-form");
            const title = modal.querySelector("#sponsor-creation-form-title");

            form.reset();
            title.textContent = "Edit Sponsor";

            const id = btn.dataset.id || "";
            const name = btn.dataset.name || "";
            const website = btn.dataset.websiteUrl || "";
            const tier = btn.dataset.tier || "";
            const description = btn.dataset.description || "";

            const logoLight = btn.dataset.logoLight || "";
            const logoDark = btn.dataset.logoDark || "";

            form.querySelector("#sponsor-id").value = id;
            form.querySelector("[name='name']").value = name;
            form.querySelector("[name='website_url']").value = website;
            form.querySelector("[name='tier']").value = tier;

            const descInput = form.querySelector("[name='description']");
            descInput.value = description;
            descInput.dataset.markdown = description;

            if (descInput.editorInstance) {
                descInput.editorInstance.setData(description);
            }

            if (logoLight && fileChoosers.logo_light) {
                fileChoosers.logo_light.renderPreview(logoLight);
            }

            if (logoDark && fileChoosers.logo_dark) {
                fileChoosers.logo_dark.renderPreview(logoDark);
            }

            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();

        });

    });

}

function enableStatusUpdate(selector) {
    const checkboxes = document.querySelectorAll(selector);
    if (checkboxes.length <= 0) return;

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const status = checkbox.checked ? 'active' : 'inactive';

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(authRoute('user.documentation.sponsor.status.update', { sponsor: checkbox.dataset.documentationSponsorUuid }), {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'x-csrf-token': csrfToken
                },
                body: JSON.stringify({ status: status })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Toastify.success(data.message);
                    } else {
                        Toastify.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toastify.error("Something went wrong!");
                });

        });
    });
}
