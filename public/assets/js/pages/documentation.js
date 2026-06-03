document.addEventListener('DOMContentLoaded', function () {
    enableTitleToUrl("#title-input", "#url-input");
    enableLogoPicker();
    enableTemplateLoading();
    enableDoneSelectionButton();

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

function enableTemplateLoading() {

    const state = {
        free: { page: 1, loading: false, hasMore: true },
        premium: { page: 1, loading: false, hasMore: true },
        my: { page: 1, loading: false, hasMore: true }
    };

    const containers = {
        free: document.getElementById('freeTemplatesContainer'),
        premium: document.getElementById('premiumTemplatesContainer'),
        my: document.getElementById('myTemplatesContainer')
    };

    async function loadTemplates(type) {

        if (state[type].loading || !state[type].hasMore) {
            return;
        }

        state[type].loading = true;

        try {

            const response = await fetch(authRoute('user.templates.get', { type: type, page: state[type].page }));

            const result = await response.json();

            if (!result.data || result.data.length === 0) {
                state[type].hasMore = false;
                return;
            }

            result.data.forEach(template => {

                const image = template.preview_image_url
                    ? template.preview_image_url
                    : 'https://placehold.co/600x300';

                let price = type != 'free' ? parseFloat(template.price || 0).toFixed(2) : 'Free';


                containers[type].insertAdjacentHTML(
                    'beforeend',
                    `
                    <div class="col">

                        <div class="template-card d-flex flex-column h-100 ${template.is_selectable ? 'selectable-template' : ''}"
                            data-template-id="${template.id}"
                            data-preview-image="${image}"
                            data-title="${template.title}"
                            data-type="${template.price == 0 || !template.price ? 'Free' : 'Premium'}"
                            data-description="${template.description}"
                        >
                            <i class='bx bxs-check-circle selected-marker' ></i>
                            <img
                                class="card-img-top rounded-top"
                                src="${image}"
                                alt="${template.title}"
                            >

                            <div class="template-meta">
                                <div>
                                    <div class="user-title">
                                        <img
                                            class="proflie-image"
                                            src="https://placehold.co/50"
                                            alt=""
                                        >

                                        <h2 class="mb-0">
                                            ${template.title}
                                        </h2>
                                    </div>

                                    <div class="d-flex gap-1">
                                        <div class="rating text-muted">
                                            <i class="bi bi-coin"></i>
                                            ${price}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    `
                );
            });

            enableTemplateSelection();

            state[type].hasMore =
                result.current_page < result.last_page;

            state[type].page++;

        } catch (error) {
            console.error('Failed to load templates:', error);
        } finally {
            state[type].loading = false;
        }
    }

    function getActiveType() {

        const activePane = document.querySelector('.tab-pane.active');

        if (!activePane) return 'free';

        switch (activePane.id) {
            case 'free-templates':
                return 'free';

            case 'premium-templates-pane':
                return 'premium';

            case 'my-templates-pane':
                return 'my';

            default:
                return 'free';
        }
    }

    document.querySelector('.modal-template-cards')
        ?.addEventListener('scroll', function () {

            if (
                this.scrollTop + this.clientHeight >=
                this.scrollHeight - 150
            ) {
                loadTemplates(getActiveType());
            }
        });

    document
        .getElementById('free-templates-tab')
        ?.addEventListener('shown.bs.tab', () => {
            if (containers.free && containers.free.children.length === 0) {
                loadTemplates('free');
            }
        });

    document
        .getElementById('premium-templates-tab')
        ?.addEventListener('shown.bs.tab', () => {
            if (containers.premium && containers.premium.children.length === 0) {
                loadTemplates('premium');
            }
        });

    document
        .getElementById('my-templates-tab')
        ?.addEventListener('shown.bs.tab', () => {
            if (containers.my && containers.my.children.length === 0) {
                loadTemplates('my');
            }
        });

    loadTemplates('free');
}

function enableTemplateSelection() {
    const btns = document.querySelectorAll('.selectable-template');

    if (btns.length <= 0) {
        return;
    }

    btns.forEach(btn => {
        if (btn.getAttribute('data-listener-added') == 'true') {
            return;
        }

        btn.addEventListener('click', function () {
            btns.forEach(b => {
                b.classList.remove('template-selected');
            });
            btn.classList.add('template-selected');
        });

        btn.setAttribute('data-listener-added', 'true');
    });
}


function enableDoneSelectionButton() {
    const btn = document.querySelector('#apply-template-button');
    const docFormTemplate = document.querySelector('.doc-form-template');

    if (!btn || !docFormTemplate) return;

    btn.addEventListener('click', function () {
        const selectedBtn = document.querySelector('.selectable-template.template-selected');

        if (!selectedBtn) {
            Toastify.error('Please select template');
            return;
        }

        docFormTemplate.querySelector('.template-image-area img').src =
            selectedBtn.dataset.previewImage;

        docFormTemplate.querySelector('.paid-type-val').textContent =
            selectedBtn.dataset.type;

        document.querySelector('#templateIdInput').value =
            selectedBtn.dataset.templateId;

        docFormTemplate.querySelector('#template-title').textContent =
            selectedBtn.dataset.title;

        docFormTemplate.querySelector('#template-description').textContent =
            selectedBtn.dataset.description;

        $("#selectTemplateModal").modal('hide');
    });
}
