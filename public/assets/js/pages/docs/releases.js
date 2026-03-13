document.addEventListener('DOMContentLoaded', function () {
    enableOnThisPageSection('.doc-releases');
});

function enableOnThisPageSection(selector) {
    const sc = new ScrollSpyControl();
    sc.generateScrollSpy(selector);
    sc.smoothScrollBehaviour();
    sc.enableScrollpsyIndicator();

}



