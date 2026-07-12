document.addEventListener('DOMContentLoaded', function () {
    enableOpenFolderButton("#OpenSelectedFolderBtn");

});

function enableOpenFolderButton(selector) {
    const btn = document.querySelector(selector);
    const parent = btn.parentElement;
    const select = parent.querySelector('select');
    const redirectLink = parent.querySelector('#redirect-link');

    if (select) {
        btn.addEventListener('click', () => {
            const selectedOption = select.options[select.selectedIndex];

            if (selectedOption) {
                const url = selectedOption.dataset.url;

                if (url) {
                    redirectLink.href = url;
                    redirectLink.click();
                } else {
                    console.warn('No url found for selected option');
                }
            } else {
                console.warn('No option selected');
            }
        });
    }

}

