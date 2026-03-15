document.addEventListener('DOMContentLoaded', function () {
    enableTitleToUrl("#title-input", "#url-input");
    enableLogoPicker();
});

function enableTitleToUrl(titleInputSelector, urlInputSelector) {
    const titleInput = document.querySelector(titleInputSelector);
    const urlInput = document.querySelector(urlInputSelector);
    if (!titleInput || !urlInput) {
        return;
    }

    titleInput.addEventListener('keyup', function () {
        if (titleInput.value.trim() != '') {
            urlInput.value = slugify(titleInput.value);
        }
    });

}

function slugify(text) {
    return text
        .toString()
        .normalize('NFKD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

function enableLogoPicker() {
    const toggle = document.getElementById("logoCustomizeToggle");
    const darkLogos = document.querySelectorAll(".doc-logo-picker .dark-logo");

    if (!toggle) {
        return;
    }

    toggle.addEventListener("change", function () {
        darkLogos.forEach(el => {
            el.classList.toggle("d-none", !this.checked);
        });
    });

    document.querySelectorAll(".doc-logo-picker label").forEach(label => {

        const input = label.querySelector("input[type='file']");
        const existingImage = input.dataset.existing;

        const pickerBox = document.createElement("div");
        pickerBox.classList.add("picker-box");

        input.insertAdjacentElement('afterend', pickerBox);

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
                e.stopPropagation();
                input.value = "";
                pickerBox.innerHTML = "<span>Click to upload</span>";
            };

            pickerBox.appendChild(img);
            pickerBox.appendChild(removeBtn);
        }

        if (existingImage) {
            renderPreview(existingImage);
        } else {
            pickerBox.innerHTML = "<span>Click to upload</span>";
        }

        input.addEventListener("change", function () {
            if (!this.files || !this.files[0]) return;

            const reader = new FileReader();
            reader.onload = e => renderPreview(e.target.result);
            reader.readAsDataURL(this.files[0]);
            label.classList.remove('has-error');
        });
    });
}
