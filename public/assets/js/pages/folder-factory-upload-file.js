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
                const slug = selectedOption.dataset.slug;

                if (slug) {
                    redirectLink.href = authRoute('user.folder-factory.files.index', { slug: slug });
                    redirectLink.click();
                } else {
                    console.warn('No slug found for selected option');
                }
            } else {
                console.warn('No option selected');
            }
        });
    }

}
