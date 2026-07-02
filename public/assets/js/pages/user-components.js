
document.addEventListener('DOMContentLoaded', function () {
    new DocSearch('#search-button');
});

class DocSearch {
    constructor(selector) {
        this.btn = document.querySelector(selector);
        if (!this.btn) return;

        this.modal = document.querySelector('#searchModal');
        if (!this.modal) return;

        this.input = this.modal.querySelector('.ux-app-search-input');
        this.body = this.modal.querySelector('.ux-app-search-body');

        this.debounceTimer = null;
        this.controller = null;

        this.currentIndex = -1;

        this.init();
    }

    init() {
        this.bindButton();
        this.bindModalEvents();
        this.bindInput();
        this.bindResultClick();
        this.enableNavigation();
    }

    bindButton() {
        const openModal = () => {
            $(this.modal).modal('show');
        };

        this.btn.addEventListener('click', openModal);

        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                openModal();
            }
        });
    }

    bindModalEvents() {
        $(this.modal).on('shown.bs.modal', () => {
            this.resetState();
            this.input.focus();
        });

        $(this.modal).on('hidden.bs.modal', () => {
            this.resetState();
        });
    }

    bindInput() {
        this.input.addEventListener('input', () => this.handleInput());
    }

    bindResultClick() {
        this.body.addEventListener('click', (e) => this.handleResultClick(e));
    }

    resetState() {
        this.input.value = '';
        this.renderSearchHistory(this.body);
    }

    handleInput() {
        const value = this.input.value.trim();

        if (value === "") {
            this.abortRequest();
            clearTimeout(this.debounceTimer);
            this.renderSearchHistory(this.body);
            return;
        }

        clearTimeout(this.debounceTimer);

        this.debounceTimer = setTimeout(() => {
            if (value !== this.input.value.trim()) return;

            this.performSearch(value);
        }, 300);
    }

    performSearch(value) {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content');

        const username = this.input.getAttribute('data-username');
        const slug = this.input.getAttribute('data-slug');
        const version = this.input.getAttribute('data-version');

        this.abortRequest();
        this.controller = new AbortController();

        this.showLoader();

        fetch(`/${username}/docs-search/${slug}/${version}?q=${encodeURIComponent(value)}`, {
            method: 'GET',
            headers: {
                'x-csrf-token': csrfToken
            },
            signal: this.controller.signal
        })
            .then(res => res.json())
            .then(data => {
                if (this.input.value.trim() !== value) return;

                this.body.innerHTML = data.success
                    ? data.html
                    : this.noResultsHTML();

                this.currentIndex = -1;
            })
            .catch(error => this.handleError(error));
    }

    abortRequest() {
        if (this.controller) {
            this.controller.abort();
            this.controller = null;
        }
    }

    showLoader() {
        this.body.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border" role="status"></div>
            </div>
        `;
    }

    noResultsHTML() {
        return `<p class="text-center text-muted fst-italic py-4">No Results...</p>`;
    }

    handleError(error) {
        if (error.name === 'AbortError') {
            if (this.input.value.trim() === "") {
                this.renderSearchHistory(this.body);
            }
            return;
        }
        console.error(error);
        this.body.innerHTML = this.noResultsHTML();
    }

    handleResultClick(e) {
        const link = e.target.closest('.ux-app-search-item');

        if (!link) return;

        this.openResultLink(link, e);
    }

    openResultLink(link, event) {
        if (!link.querySelector('a.ux-app-search-item-link')) {
            const item = {
                title: link.querySelector('.ux-app-search-title')?.innerText || '',
                meta: link.querySelector('.ux-app-search-meta')?.innerText || '',
                url: link.href,
                folder: link.closest('.ux-app-search-folder')
                    ?.querySelector('.ux-app-search-folder-title')?.innerText || 'General'
            };

            this.saveSearchHistory(item);
        }

        const url = new URL(link.getAttribute('data-url'));
        const current = new URL(window.location.href);

        const isSamePage =
            url.pathname === current.pathname &&
            url.search === current.search;

        $(this.modal).modal('hide');

        if (isSamePage) {
            event.preventDefault();

            const targetId = url.hash.replace('#', '');
            const el = document.getElementById(targetId);

            if (el) {
                setTimeout(() => {
                    el.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    history.pushState(null, '', url.hash);

                    el.classList.add('search-hit');
                    setTimeout(() => el.classList.remove('search-hit'), 1500);

                }, 300);
            }
        }
    }

    saveSearchHistory(item) {
        const key = 'doc_search_history';
        let history = JSON.parse(localStorage.getItem(key)) || [];

        history = history.filter(i => i.url !== item.url);

        history.unshift(item);

        if (history.length > 10) {
            history = history.slice(0, 10);
        }

        localStorage.setItem(key, JSON.stringify(history));
    }

    renderSearchHistory(container) {
        const key = 'doc_search_history';
        let history = JSON.parse(localStorage.getItem(key)) || [];

        if (!history.length) {
            container.innerHTML = `
                <p class="text-center text-muted fst-italic py-4">
                    No recent searches
                </p>
            `;
            return;
        }

        const groups = {};
        history.forEach(item => {
            if (!groups[item.folder]) groups[item.folder] = [];
            groups[item.folder].push(item);
        });

        let html = `
            <div class="ux-app-search-group">
                <h6 class="ux-app-search-folder-title text-muted px-2 my-1">
                    Recent
                </h6>
        `;

        Object.keys(groups).forEach(folder => {
            html += `<div class="ux-app-search-folder mb-1">`;

            groups[folder].forEach(item => {
                html += `
                <div class="ux-app-search-item px-2 py-2" data-url="${item.url}">

                    <a href="${item.url}" class="ux-app-search-item-link d-flex align-items-center text-decoration-none flex-grow-1">
                        <div class="left-icon-box">
                            <i class='bx bx-recent-clock'></i>
                        </div>

                        <div>
                            <div class="ux-app-search-title fw-semibold">
                                ${item.title}
                            </div>

                            <div class="ux-app-search-meta small text-muted">
                                ${item.meta}
                            </div>
                        </div>
                    </a>

                    <div class="right-icon-x-box ms-auto" role="button">
                        <i class="bx bx-x"></i>
                    </div>

                </div>
            `;
            });

            html += `</div>`;
        });

        html += `</div>`;

        container.innerHTML = html;
        this.currentIndex = -1;

        if (!container.dataset.historyBound) {
            container.dataset.historyBound = "true";

            container.addEventListener('click', (e) => {
                const removeBtn = e.target.closest('.right-icon-x-box');
                if (!removeBtn) return;

                console.log('here-x', removeBtn);

                e.preventDefault();
                e.stopPropagation();

                const itemEl = removeBtn.closest('.ux-app-search-item');
                const url = itemEl.getAttribute('data-url');

                let history = JSON.parse(localStorage.getItem(key)) || [];

                history = history.filter(item => item.url !== url);

                localStorage.setItem(key, JSON.stringify(history));

                this.renderSearchHistory(container);
            });
        }
    }

    enableNavigation() {
        const input = document.querySelector('#search-input');
        const container = document.querySelector('.ux-app-search-body');
        const classObj = this;

        function getItems() {
            return container.querySelectorAll('.ux-app-search-item');
        }

        function setActive(index) {
            const items = getItems();

            items.forEach(item => item.classList.remove('active'));

            if (items[index]) {
                items[index].classList.add('active');
                items[index].scrollIntoView({
                    block: 'nearest'
                });
            }
        }

        input.addEventListener('keydown', (e) => {
            const items = getItems();
            if (!items.length) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                classObj.currentIndex = (classObj.currentIndex + 1) % items.length;
                setActive(classObj.currentIndex);
            }

            if (e.key === 'ArrowUp') {
                e.preventDefault();
                classObj.currentIndex =
                    (classObj.currentIndex - 1 + items.length) % items.length;
                setActive(classObj.currentIndex);
            }

            if (e.key === 'Enter') {
                if (classObj.currentIndex >= 0 && items[classObj.currentIndex]) {
                    const linkItem = document.querySelector('.ux-app-search-body .ux-app-search-item.active');
                    if (linkItem) {
                        classObj.openResultLink(linkItem, e);
                    }

                    const url = items[classObj.currentIndex].dataset.url;
                    if (url) window.location.href = url;
                }
            }
        });

        container.addEventListener('mouseover', (e) => {
            const item = e.target.closest('.ux-app-search-item');
            if (!item) return;

            const items = Array.from(getItems());
            classObj.currentIndex = items.indexOf(item);
            setActive(classObj.currentIndex);
        });
    }
}
