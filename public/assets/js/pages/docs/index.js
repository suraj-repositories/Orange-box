enableDarkTheme("#themeToggle");
document.addEventListener('DOMContentLoaded', function () {

    generateScrollSpy();
    smoothScrollBehaviour();
    enableScrollpsyIndicator();
});
function enableDarkTheme(selector) {

    const toggle = document.querySelector(selector);
    const body = document.body;
    const storageKey = "user-theme";

    const systemDark = window.matchMedia("(prefers-color-scheme: dark)");

    function applyTheme(theme) {
        body.setAttribute("data-theme", theme);
        localStorage.setItem(storageKey, theme);

        if (toggle) {
            toggle.checked = theme === "dark";
        }
    }

    function getSavedTheme() {
        return localStorage.getItem(storageKey);
    }

    const savedTheme = getSavedTheme();

    if (savedTheme) {
        applyTheme(savedTheme);
    } else {
        const defaultTheme = systemDark.matches ? "dark" : "light";
        applyTheme(defaultTheme);
    }

    if (toggle) {
        toggle.addEventListener("change", function () {
            const theme = this.checked ? "dark" : "light";
            applyTheme(theme);
        });
    }

    systemDark.addEventListener("change", function (e) {
        if (!getSavedTheme()) {
            applyTheme(e.matches ? "dark" : "light");
        }
    });
}


function smoothScrollBehaviour() {
    document.querySelectorAll('#navbar-example3 a').forEach(anchor => {
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
    const navContainer = document.querySelector('#navbar-example3 .nav');

    if (!content || !navContainer) return;

    navContainer.innerHTML = '<div class="active-indicator"></div>';


    const headings = content.querySelectorAll('h2, h3');

    let currentParentNav = null;

    headings.forEach((heading, index) => {

        if (!heading.id) {
            heading.id = heading.textContent
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');
        }

        if (heading.tagName === 'H2') {

            const parentLink = document.createElement('a');
            parentLink.className = 'nav-link';
            parentLink.href = `#${heading.id}`;
            parentLink.textContent = heading.textContent;

            navContainer.appendChild(parentLink);

            currentParentNav = document.createElement('nav');
            currentParentNav.className = 'nav nav-pills flex-column';
            navContainer.appendChild(currentParentNav);

        } else if (heading.tagName === 'H3' && currentParentNav) {

            const childLink = document.createElement('a');
            childLink.className = 'nav-link ms-2';
            childLink.href = `#${heading.id}`;
            childLink.textContent = heading.textContent;

            currentParentNav.appendChild(childLink);
        }
    });

    new bootstrap.ScrollSpy(document.body, {
        target: '#navbar-example3',
        offset: 100
    });
}
function enableScrollpsyIndicator() {
    const navContainer = document.querySelector("#navbar-example3 .nav");
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

