class EmojiChooser {
    constructor(container) {
        this.container = container;
        this.emojis = {};
    }

    async init() {
        await this.createPicker();
        this.bindEvents();
    }

    slugify(text) {
        return text
            .toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[^\w-]/g, '');
    }

    async createPicker() {
        try {
            const response = await fetch(route('emojis'));
            const result = await response.json();

            this.emojis = result.data || {};
        } catch (error) {
            console.error('Failed to load emojis:', error);
            return;
        }

        const categories = Object.entries(this.emojis);

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
                ${categories.map(([category, items], index) => {
                    const icon = Object.keys(items)[0] || '😀';

                    return `
                        <li class="nav-item">
                            <button
                                type="button"
                                class="nav-link ${index === 0 ? 'active' : ''}"
                                data-target="${this.slugify(category)}"
                                title="${category}">
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
                        🖼️
                    </button>
                </li>
            </ul>

            <div class="emoji-grid-container">
                ${categories.map(([category, items]) => `
                    <section
                        class="emoji-category"
                        id="${this.slugify(category)}">

                        <div class="emoji-category-title">
                            ${category}
                        </div>

                        <div class="emoji-category-grid">
                            ${Object.entries(items).map(([emoji, name]) => `
                                <button
                                    type="button"
                                    class="emoji-item"
                                    data-name="${name.toLowerCase()}"
                                    title="${name}">
                                    ${emoji}
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
                        <input type="file" hidden accept="image/*">

                        <i class="ri-upload-cloud-line"></i>

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

            const emoji = emojiBtn.textContent.trim();

            wrapper.dispatchEvent(
                new CustomEvent('emoji:selected', {
                    detail: { emoji }
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

        const grid = this.container.querySelector(
            '.emoji-grid-container'
        );

        if (grid) {
            const observer = new IntersectionObserver(
                entries => {
                    entries.forEach(entry => {

                        if (!entry.isIntersecting) {
                            return;
                        }

                        this.container
                            .querySelectorAll('.nav-link')
                            .forEach(btn =>
                                btn.classList.remove('active')
                            );

                        this.container
                            .querySelector(
                                `[data-target="${entry.target.id}"]`
                            )
                            ?.classList.add('active');
                    });
                },
                {
                    root: grid,
                    threshold: 0.3
                }
            );

            grid.querySelectorAll('.emoji-category')
                .forEach(section => observer.observe(section));
        }
    }
}

document
    .querySelectorAll('.emoji-picker-container')
    .forEach(async el => {
        const picker = new EmojiChooser(el);
        await picker.init();
    });
