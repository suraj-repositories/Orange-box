document.addEventListener('DOMContentLoaded', function () {
    enableTitleToUrl("#title-input", "#url-input");
});

function enableTitleToUrl(titleInputSelector, urlInputSelector) {
    const titleInput = document.querySelector(titleInputSelector);
    const urlInput = document.querySelector(urlInputSelector);
    if (!titleInput || !urlInput) {
        return;
    }

    titleInput.addEventListener('keyup', function () {
        if (titleInput.value.trim() != '') {
            urlInput.value = slugify(titleInput.value);
        }
    });

}

function slugify(text) {
    return text
        .toString()
        .normalize('NFKD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
}
