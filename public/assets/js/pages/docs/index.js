enableDarkTheme("#themeToggle");

document.addEventListener('DOMContentLoaded', function () {
    enableSidebarBackdropCloseable();
    generateScrollSpy();
    smoothScrollBehaviour();
    enableScrollpsyIndicator();
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


function smoothScrollBehaviour() {
    document.querySelectorAll('#scrollpsy-nav a').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href'))
                .scrollIntoView({
                    behavior: 'smooth'
                });
        });
    });
}

function generateScrollSpy() {

    const content = document.getElementById('documentationContent');
    const navContainer = document.querySelector('#scrollpsy-nav .nav');
    const dropdownMenu = document.getElementById('onThisPageMenu');

    if (!content || !navContainer) return;

    const navbarHeight = 70;

    navContainer.innerHTML = '<div class="active-indicator"></div>';
    if (dropdownMenu) dropdownMenu.innerHTML = '';

    const allHeadings = content.querySelectorAll('h1, h2, h3, h4, h5, h6');
    const spyHeadings = content.querySelectorAll('h2, h3');

    let currentParentNav = null;

    allHeadings.forEach((heading) => {

        if (!heading.id) {
            heading.id = heading.textContent
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');
        }

        const headingId = `#${heading.id}`;

        heading.style.cursor = 'pointer';

        heading.addEventListener('click', function () {
            const targetPosition = this.getBoundingClientRect().top + window.pageYOffset;

            window.scrollTo({
                top: targetPosition - navbarHeight,
                behavior: 'smooth'
            });

            history.replaceState(null, null, headingId);
        });

    });

    spyHeadings.forEach((heading) => {

        const headingId = `#${heading.id}`;
        const headingText = heading.textContent;

        if (heading.tagName === 'H2') {

            const parentLink = document.createElement('a');
            parentLink.className = 'nav-link';
            parentLink.href = headingId;
            parentLink.textContent = headingText;

            navContainer.appendChild(parentLink);

            currentParentNav = document.createElement('nav');
            currentParentNav.className = 'nav nav-pills flex-column';
            navContainer.appendChild(currentParentNav);

        } else if (heading.tagName === 'H3' && currentParentNav) {

            const childLink = document.createElement('a');
            childLink.className = 'nav-link ms-2';
            childLink.href = headingId;
            childLink.textContent = headingText;

            currentParentNav.appendChild(childLink);
        }

        if (dropdownMenu) {

            const li = document.createElement('li');
            const dropdownLink = document.createElement('a');
            dropdownLink.className = 'dropdown-item';

            if (heading.tagName === 'H3') {
                dropdownLink.classList.add('ps-4');
            }

            dropdownLink.href = headingId;
            dropdownLink.textContent = headingText;

            li.appendChild(dropdownLink);
            dropdownMenu.appendChild(li);
        }

    });

    new bootstrap.ScrollSpy(document.body, {
        target: '#scrollpsy-nav',
        offset: navbarHeight
    });
}


function enableScrollpsyIndicator() {
    const navContainer = document.querySelector("#scrollpsy-nav .nav");
    const indicator = navContainer.querySelector(".active-indicator");

    function moveIndicator() {

        const activeLinks = navContainer.querySelectorAll(".nav-link.active");

        if (!activeLinks.length) return;

        const active = activeLinks[activeLinks.length - 1];

        const containerRect = navContainer.getBoundingClientRect();
        const activeRect = active.getBoundingClientRect();

        const offset = activeRect.top - containerRect.top + navContainer.scrollTop;

        indicator.style.transform = `translateY(${offset}px)`;
        indicator.style.height = active.offsetHeight + "px";
    }

    document.addEventListener("scroll", moveIndicator);
    window.addEventListener("resize", moveIndicator);

    moveIndicator();
}

function enableFeedbackBtns(selector) {
    const feedbackSection = document.querySelector(selector);
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


