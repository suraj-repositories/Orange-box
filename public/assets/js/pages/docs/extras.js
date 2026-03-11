document.addEventListener('DOMContentLoaded', function () {
    enableOnThisPageSection("#documentationDocumentContent");
});

function enableOnThisPageSection(selector) {
    const pageContent = document.querySelector(selector);

    const observer = new MutationObserver(() => {
        if (pageContent.classList.contains('editorjs-parsed')) {

            const sc = new ScrollSpyControl();
            sc.generateScrollSpy(selector);
            sc.smoothScrollBehaviour();
            sc.enableScrollpsyIndicator();

            observer.disconnect();
        }
    });

    observer.observe(pageContent, {
        attributes: true,
        attributeFilter: ['class']
    });
}



