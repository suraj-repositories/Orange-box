enableDarkTheme("#themeToggle");

document.addEventListener('DOMContentLoaded', function () {
    enableSidebarBackdropCloseable();
    enableScrollSpy("#documentationContent");
    enableFeedbackBtns(".feedback-card");
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
| On This page - Start
---------------------------------------------- */

function enableScrollSpy(contentSelector) {

    const sc = new ScrollSpyControl();
    sc.generateScrollSpy(contentSelector);
    sc.smoothScrollBehaviour();
    sc.enableScrollpsyIndicator();

}

/* -------------------------------------------
| On This page - END
---------------------------------------------- */
