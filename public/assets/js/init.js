(function () {
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
