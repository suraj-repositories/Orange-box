document.addEventListener('DOMContentLoaded', function () {
    initFileChoosers();
    enableStatusUpdate('.partnerStatusToggleSwitch');
    enableSpotlightUpdate('.spotlightUpdateRadio');
    initTagify();
});

let fileChoosers = {};

function initFileChoosers() {

    const labels = document.querySelectorAll(".partners-media-picker label");

    if (labels.length <= 0) {
        return;
    }

    labels.forEach(label => {

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
        const existingImage = input.dataset.existing;

        if (existingImage) {
            renderPreview(existingImage);
        }

    });

}

function enableStatusUpdate(selector) {
    const checkboxes = document.querySelectorAll(selector);
    if (checkboxes.length <= 0) return;

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const status = checkbox.checked ? 'active' : 'inactive';

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(authRoute('user.documentation.partner.status.update', { partner: checkbox.dataset.documentationPartnerId }), {
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

function enableSpotlightUpdate(selector) {
    const radios = document.querySelectorAll(selector);
    if (radios.length <= 0) return;

    radios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (radio.checked) {

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(authRoute('user.documentation.partner.mark-spotlight', { partner: radio.dataset.documentationPartnerId }), {
                    method: 'PATCH',
                    headers: {
                        'x-csrf-token': csrfToken
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Toastify.success(data.message);
                            window.location.reload();
                        } else {
                            Toastify.error(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Toastify.error("Something went wrong!");
                    });
            }

        });
    });
}

function initTagify() {
    const input = document.querySelector('#tags-input');

    const tagify = new Tagify(input, {
        maxTags: 10,
        dropdown: {
            enabled: 0
        }
    });
}
