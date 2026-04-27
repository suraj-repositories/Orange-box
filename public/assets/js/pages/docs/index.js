enableDarkTheme("#themeToggle");

document.addEventListener('DOMContentLoaded', function () {
    new DocSearch('#search-button');

    enableSidebarBackdropCloseable();
    enableScrollSpy("#documentationContent");
    enableFeedbackSystem(".feedback-card");
    enableFullScreenNav();
});

function enableSidebarBackdropCloseable() {
    const sidebarBackdrop = document.querySelector('.app-sidebar-menu');
    if (!sidebarBackdrop) return;

    const mediaQuery = window.matchMedia("(max-width: 1040px)");

    function handleClick(e) {
        if (!e.target.closest('.simplebar-content')) {
            document.documentElement.setAttribute('data-sidebar', 'hidden');
        }
    }

    function checkScreen(e) {
        if (e.matches) {
            sidebarBackdrop.addEventListener('click', handleClick);
        } else {
            sidebarBackdrop.removeEventListener('click', handleClick);
        }
    }

    checkScreen(mediaQuery);
    mediaQuery.addEventListener('change', checkScreen);
}

function enableDarkTheme(selector) {

    const toggle = document.querySelector(selector);
    const storageKey = "user-theme";
    const systemDark = window.matchMedia("(prefers-color-scheme: dark)");

    function applyTheme(theme, save = true) {
        document.documentElement.setAttribute("data-theme", theme);

        if (save) {
            localStorage.setItem(storageKey, theme);
        }

        if (toggle) {
            toggle.checked = theme === "dark";
        }
    }

    function getSavedTheme() {
        return localStorage.getItem(storageKey);
    }

    // Sync toggle on load
    const currentTheme = document.documentElement.getAttribute("data-theme");
    if (toggle) {
        toggle.checked = currentTheme === "dark";
    }

    // Toggle click
    if (toggle) {
        toggle.addEventListener("change", function () {
            const theme = this.checked ? "dark" : "light";
            applyTheme(theme);
        });
    }

    // System change (only if user never saved preference)
    systemDark.addEventListener("change", function (e) {
        if (!getSavedTheme()) {
            applyTheme(e.matches ? "dark" : "light", false);
        }
    });
}

function enableFeedbackBtns(selector) {
    const feedbackSection = document.querySelector(selector);
    if (!feedbackSection) return;
    const btns = feedbackSection.querySelectorAll('.feedback-btns button');
    if (btns) {
        btns.forEach(btn => {
            btn.addEventListener('click', () => {
                if (btn.classList.contains('active')) {
                    btn.classList.remove('active');
                } else {
                    btns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                }
            });
        });
    }
}

function enableFeedbackSystem(selector) {
    const section = document.querySelector(selector);
    if (!section) return;

    const buttons = section.querySelectorAll('.feedback-btns button');
    const textarea = section.querySelector('textarea');
    const submitBtn = section.querySelector('.card-footer button');

    let selectedRating = null;

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            if (btn.classList.contains('active')) {
                btn.classList.remove('active');
                selectedRating = null;
            } else {
                buttons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                selectedRating = btn.dataset.rating;
            }
        });
    });

    submitBtn.addEventListener('click', async () => {
        const pageId = section.dataset.pageId;
        const feedbackText = textarea.value;

        if (!selectedRating) {
            alert('Please select a rating');
            return;
        }

        try {
            submitBtn.disabled = true;
            submitBtn.innerText = 'Sending...';

            const response = await fetch('/feedback/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    documentation_page_id: pageId,
                    rating: selectedRating,
                    feedback: feedbackText
                })
            });

            const data = await response.json();

            if (data.success) {

                textarea.value = '';
                buttons.forEach(b => b.classList.remove('active'));
                selectedRating = null;
                section.classList.add('feedback-send-success');

                setTimeout(() => {
                    section.classList.remove('feedback-send-success');
                }, 3000);

            } else {
                alert(data.message || 'Something went wrong');

            }
            submitBtn.innerText = 'Send';

        } catch (error) {
            console.error(error);
            alert('Network error');
            submitBtn.innerText = 'Send';
        } finally {
            submitBtn.disabled = false;
        }
    });
}

function enableFullScreenNav() {
    const btn = document.querySelector('#fullScreenNavToggle');
    if (btn) {
        btn.addEventListener('click', function () {
            const navbar = document.body.getAttribute('data-navbar');
            if (navbar == 'full') {
                document.body.setAttribute('data-navbar', 'default');
            } else {
                document.body.setAttribute('data-navbar', 'full');

            }
        });
    }
}

/* -------------------------------------------
|           On This page - Start             |
------------------------------------------- */

function enableScrollSpy(contentSelector) {

    const sc = new ScrollSpyControl();
    sc.generateScrollSpy(contentSelector);
    sc.smoothScrollBehaviour();
    sc.enableScrollpsyIndicator();

}

/* -------------------------------------------
| On This page - END
---------------------------------------------- */

class DocSearch {
    constructor(selector) {
        this.btn = document.querySelector(selector);
        if (!this.btn) return;

        this.modal = document.querySelector('#searchModal');
        if (!this.modal) return;

        this.input = this.modal.querySelector('.ux-search-input');
        this.body = this.modal.querySelector('.ux-search-body');

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
        const link = e.target.closest('.ux-search-item');

        if (!link) return;

        this.openResultLink(link, e);
    }

    openResultLink(link, event) {
        if (!link.querySelector('a.ux-search-item-link')) {
            const item = {
                title: link.querySelector('.ux-search-title')?.innerText || '',
                meta: link.querySelector('.ux-search-meta')?.innerText || '',
                url: link.href,
                folder: link.closest('.ux-search-folder')
                    ?.querySelector('.ux-search-folder-title')?.innerText || 'General'
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
            <div class="ux-search-group">
                <h6 class="ux-search-folder-title text-muted px-2 my-1">
                    Recent
                </h6>
        `;

        Object.keys(groups).forEach(folder => {
            html += `<div class="ux-search-folder mb-1">`;

            groups[folder].forEach(item => {
                html += `
                <div class="ux-search-item px-2 py-2" data-url="${item.url}">

                    <a href="${item.url}" class="ux-search-item-link d-flex align-items-center text-decoration-none flex-grow-1">
                        <div class="left-icon-box">
                            <i class='bx bx-recent-clock'></i>
                        </div>

                        <div>
                            <div class="ux-search-title fw-semibold">
                                ${item.title}
                            </div>

                            <div class="ux-search-meta small text-muted">
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

                const itemEl = removeBtn.closest('.ux-search-item');
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
        const container = document.querySelector('.ux-search-body');
        const classObj = this;

        function getItems() {
            return container.querySelectorAll('.ux-search-item');
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
                    const linkItem = document.querySelector('.ux-search-body .ux-search-item.active');
                    if (linkItem) {
                        classObj.openResultLink(linkItem, e);
                    }

                    const url = items[classObj.currentIndex].dataset.url;
                    if (url) window.location.href = url;
                }
            }
        });

        container.addEventListener('mouseover', (e) => {
            const item = e.target.closest('.ux-search-item');
            if (!item) return;

            const items = Array.from(getItems());
            classObj.currentIndex = items.indexOf(item);
            setActive(classObj.currentIndex);
        });
    }
}

