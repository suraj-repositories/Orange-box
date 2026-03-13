document.addEventListener('DOMContentLoaded', function () {
    enableOnThisPageSection('.faq-questions');
});

function enableOnThisPageSection(selector) {
    const sc = new ScrollSpyControl();
    sc.generateScrollSpy(selector);
    sc.smoothScrollBehaviour();
    sc.enableScrollpsyIndicator();

}
