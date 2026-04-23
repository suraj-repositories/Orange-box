enableDarkTheme("#themeToggle");

document.addEventListener('DOMContentLoaded', function () {
    enableSearch('#search-button');
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

function enableSearch(selector) {
    const btn = document.querySelector(selector);
    if (!btn) {
        return;
    }
    const modal = document.querySelector('#searchModal');
    if (!modal) {
        return;
    }
    const input = modal.querySelector('.ux-search-input');
    const body = modal.querySelector('.ux-search-body');

    btn.addEventListener('click', function () {
        $(modal).modal('show');

    });

    $(modal).on('shown.bs.modal', function () {
        input.value = '';
        input.focus();
    });

    $(modal).on('hidden.bs.modal', function () {
        input.value = '';
    });

    input.addEventListener('input', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        body.innerHTML = `<div class="text-center py-4">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>`;

        fetch(`/docs/search?q=${input.value}`, {
            method: 'GET',
            headers: {
                'x-csrf-token': csrfToken
            },
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (body) {
                        body.innerHTML = data.html;
                    }
                } else {
                    if (body) {
                        body.innerHTML = ` <p class="text-center text-muted fst-italic py-4">No Results...</p>`;
                    }
                }
            })
            .catch(error => {
                if (body) {
                    body.innerHTML = ` <p class="text-center text-muted fst-italic py-4">No Results...</p>`;
                }
                console.error('Error:', error);
            });
    });
}
