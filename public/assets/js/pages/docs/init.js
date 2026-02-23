(function () {
    const storageKey = "user-theme";
    const savedTheme = localStorage.getItem(storageKey);

    if (savedTheme) {
        document.documentElement.setAttribute("data-theme", savedTheme);
    } else {
        const systemDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
        document.documentElement.setAttribute("data-theme", systemDark ? "dark" : "light");
    }

    initMenu();
})();


function initMenu() {
    var body = document.documentElement;
    if (window.innerWidth < 1040) {
        body.setAttribute('data-sidebar', 'hidden');
    } else {
        body.setAttribute('data-sidebar', 'default');
    }
}
