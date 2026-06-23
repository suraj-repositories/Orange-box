class EmojiChooser {
    constructor(container) {
        this.container = container;
        this.emojis = {};
    }

    async init() {
        await this.createPicker();
        this.bindEvents();
    }

    async createPicker() {
        try {
            const response = await fetch(route('emojis'));
            const result = await response.json();

            this.emojis = result.data || [];
        } catch (error) {
            console.error('Failed to load emojis:', error);
            return;
        }

        const picker = document.createElement('div');
        picker.className = 'emoji-image-box';

        picker.innerHTML = `
        <div class="emoji-search">
            <input
                type="text"
                class="form-control emoji-search-input"
                placeholder="Search emoji...">
        </div>

        <ul class="nav nav-pills emoji-tabs flex-nowrap">
            ${this.emojis.map((category, index) => {
            const icon = category.emojis?.[0]?.emoji || '😀';

            return `
                    <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link ${index === 0 ? 'active' : ''}"
                            data-target="category-${category.id}"
                            title="${category.name}">
                            ${icon}
                        </button>
                    </li>
                `;
        }).join('')}

            <li class="nav-item">
                <button
                    type="button"
                    class="nav-link"
                    data-target="image-upload"
                    title="Upload Image">
                    <span class="rotate-270">➜]</span>
                </button>
            </li>
        </ul>

        <div class="emoji-grid-container">
            ${this.emojis.map(category => `
                <section
                    class="emoji-category"
                    id="category-${category.id}">

                    <div class="emoji-category-title">
                        ${category.name}
                    </div>

                    <div class="emoji-category-grid">
                        ${(category.emojis || []).map(emoji => `
                            <button
                                type="button"
                                class="emoji-item"
                                data-emoji-id="${emoji.id}"
                                data-name="${emoji.name.toLowerCase()}"
                                title="${emoji.name}">
                                ${emoji.emoji}
                            </button>
                        `).join('')}
                    </div>
                </section>
            `).join('')}

                <section
                    class="emoji-category"
                    id="image-upload">

                    <div class="emoji-category-title">
                        Upload Image
                    </div>

                    <label class="upload-box">
                        <input
                            type="file"
                            hidden
                            accept=".png,.jpeg,.jpg,.ico,.webp,.avif,image/png,image/jpeg,image/x-icon,image/webp,image/avif">

                        <i class="bi bi-upload"></i>

                        <span>Upload Image</span>
                    </label>
                </section>
            </div>
        `;

        this.container.appendChild(picker);
    }

    positionPicker(wrapper) {
        const box = wrapper.querySelector('.emoji-image-box');

        if (!box || window.innerWidth <= 576) {
            return;
        }

        box.style.left = '';
        box.style.right = '0';

        requestAnimationFrame(() => {
            const rect = box.getBoundingClientRect();

            if (rect.left < 10) {
                box.style.right = 'auto';
                box.style.left = '0';
            }

            if (rect.right > window.innerWidth - 10) {
                box.style.left = 'auto';
                box.style.right = '0';
            }
        });
    }

    bindEvents() {
        const wrapper = this.container.closest('.emoji-image-picker');
        const button = wrapper?.querySelector('.emoji-picker-btn');

        button.innerHTML = "😊";

        button?.addEventListener('click', e => {
            e.stopPropagation();

            document
                .querySelectorAll('.emoji-image-picker.open')
                .forEach(item => {
                    if (item !== wrapper) {
                        item.classList.remove('open');
                    }
                });


            wrapper.classList.toggle('open');

            if (wrapper.classList.contains('open')) {
                this.positionPicker(wrapper);
            }
        });


        window.addEventListener('resize', () => {
            if (wrapper?.classList.contains('open')) {
                this.positionPicker(wrapper);
            }
        });

        this.container.addEventListener('click', e => {
            e.stopPropagation();
        });

        document.addEventListener('click', () => {
            wrapper?.classList.remove('open');
        });

        this.container.addEventListener('click', e => {
            const tab = e.target.closest('.nav-link');

            if (!tab) {
                return;
            }

            const target = this.container.querySelector(
                `#${tab.dataset.target}`
            );

            target?.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });

        this.container.addEventListener('click', e => {
            const emojiBtn = e.target.closest('.emoji-item');

            if (!emojiBtn) {
                return;
            }

            wrapper.dispatchEvent(
                new CustomEvent('emoji:selected', {
                    detail: {
                        id: emojiBtn.dataset.emojiId,
                        emoji: emojiBtn.textContent.trim(),
                        name: emojiBtn.dataset.name
                    }
                })
            );

            wrapper.classList.remove('open');
        });

        const searchInput = this.container.querySelector(
            '.emoji-search-input'
        );

        searchInput?.addEventListener('input', e => {
            const term = e.target.value.trim().toLowerCase();

            this.container
                .querySelectorAll('.emoji-item')
                .forEach(item => {
                    const name = item.dataset.name || '';

                    item.style.display =
                        !term || name.includes(term)
                            ? ''
                            : 'none';
                });

            this.container
                .querySelectorAll('.emoji-category')
                .forEach(category => {

                    const visibleEmojis = category.querySelectorAll(
                        '.emoji-item:not([style*="display: none"])'
                    );

                    if (category.id === 'image-upload') {
                        return;
                    }

                    category.style.display =
                        visibleEmojis.length > 0
                            ? ''
                            : 'none';
                });
        });

        const grid = this.container.querySelector('.emoji-grid-container');

        grid.addEventListener('scroll', () => {
            const sections = [
                ...grid.querySelectorAll('.emoji-category')
            ];

            let activeSection = sections[0];

            sections.forEach(section => {
                if (grid.scrollTop >= section.offsetTop - 20) {
                    activeSection = section;
                }
            });

            this.container
                .querySelectorAll('.nav-link')
                .forEach(btn => btn.classList.remove('active'));

            this.container
                .querySelector(
                    `[data-target="${activeSection.id}"]`
                )
                ?.classList.add('active');
        });
    }

}

document.addEventListener('DOMContentLoaded', function () {
    enableEmojiPicker();
    handleEmojiPick();
});

function enableEmojiPicker() {
    const pickerContainers = document
        .querySelectorAll('.emoji-picker-container');

    if (pickerContainers.length === 0) return;
    pickerContainers.forEach(async el => {
        const picker = new EmojiChooser(el);
        await picker.init();
        handleImageUpload();
    });
}

function handleEmojiPick() {
    const pickerWrapper = document.querySelector('.emoji-image-picker');
    const emojiPickerButton = document.querySelector('.emoji-picker-btn');

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');

    if (!pickerWrapper || !emojiPickerButton) {
        return;
    }

    const url = emojiPickerButton.dataset.emojiSubmitUrl;

    pickerWrapper.addEventListener('emoji:selected', async e => {
        const selectedEmoji = e.detail.emoji;

        const avatarContainer = document.querySelector('.post-avatar-container');
        const previousHtml = avatarContainer.innerHTML;

        renderEmojiAvatar(selectedEmoji);

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    emoji_id: e.detail.id
                })
            });

            const data = await response.json();

            if (!data.success) {
                avatarContainer.innerHTML = previousHtml;
                Toastify.error(data.message);
                return;
            }

            Toastify.success(data.message);

        } catch (error) {
            avatarContainer.innerHTML = previousHtml;
            Toastify.error('Something went wrong!');
        }
    });
}

function handleImageUpload() {
    const uploadInput = document.querySelector('.upload-box input[type="file"]');
    const uploadBox = document.querySelector('.upload-box');
    const emojiPickerButton = document.querySelector('.emoji-picker-btn');


    if (!uploadInput || !uploadBox || !emojiPickerButton) {
        return;
    }

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');


    const url = emojiPickerButton.dataset.fileSubmitUrl;

    uploadInput.addEventListener('change', async function () {
        const file = this.files[0];

        if (!file) {
            return;
        }

        const originalContent = uploadBox.innerHTML;

        uploadBox.classList.add('uploading');
        uploadBox.innerHTML = `
            <i class="spinner-border spinner-border-sm"></i>
            <span>Uploading...</span>
        `;

        const formData = new FormData();
        formData.append('image', file);

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message);
            }

            renderImageAvatar(data.url);

            uploadBox.classList.remove('uploading');
            uploadBox.classList.add('success');

            uploadBox.innerHTML = `
                <i class="bi bi-check-circle-fill"></i>
                <span>Uploaded</span>
            `;

            Toastify.success(data.message);

        } catch (error) {
            uploadBox.classList.remove('uploading');
            uploadBox.classList.add('error');

            uploadBox.innerHTML = `
                <i class="bi bi-x-circle-fill"></i>
                <span>Upload Failed</span>
            `;

            Toastify.error(error.message || 'Upload failed');
        }

        setTimeout(() => {
            uploadBox.classList.remove(
                'uploading',
                'success',
                'error'
            );

            uploadBox.innerHTML = originalContent;

            handleImageUpload();
        }, 2000);

        this.value = '';
    });
}

function resetUploadBox(uploadBox) {
    uploadBox.classList.remove('uploading', 'success', 'error');

    uploadBox.innerHTML = `
        <input
            type="file"
            hidden
            accept=".png,.jpeg,.jpg,.ico,.webp,.avif,image/png,image/jpeg,image/x-icon,image/webp,image/avif">
        <i class="bi bi-upload"></i>
        <span>Upload Image</span>
    `;

    handleImageUpload();
}


function renderEmojiAvatar(emoji) {
    const container = document.querySelector('.post-avatar-container');

    if (!container) {
        return;
    }

    container.innerHTML = `
        <div
            class="rounded-circle avatar-xxl img-thumbnail float-start d-flex align-items-center justify-content-center">
            <div class="emoji selected-emoji">${emoji}</div>
        </div>
    `;
}

function renderImageAvatar(imageUrl) {
    const container = document.querySelector('.post-avatar-container');

    if (!container) {
        return;
    }

    container.innerHTML = `
        <img
            src="${imageUrl}"
            class="rounded-circle avatar-xxl img-thumbnail float-start digest-image"
            alt="image profile">
    `;
}
